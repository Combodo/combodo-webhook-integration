<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Service;

use Combodo\iTop\Core\WebRequest;
use Combodo\iTop\Core\WebResponse;
use ContextTag;
use Exception;
use IssueLog;
use MetaModel;
use SendWebRequest;
use utils;

/**
 * Class WebRequestSender
 *
 * @package Combodo\iTop\Service
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class WebRequestSender
{
	/** @var int Request sent successfully */
	const ENUM_SEND_STATE_OK = 0;
	/** @var int Request still pending */
	const ENUM_SEND_STATE_PENDING = 1;
	/** @var int Request could not be sent due to an error */
	const ENUM_SEND_STATE_ERROR = 2;

	/** @var string Request to be sent synchronously = immediately, blocking the current script execution while sending (eg. network wait) */
	const ENUM_SEND_MODE_SYNC = 'sync';
	/** @var string Request to be sent asynchronously = by the CRON job to avoid blocking script execution */
	const ENUM_SEND_MODE_ASYNC = 'async';

	/** @var string \ContextTag placed around the ResponseHandler call */
	const CONTEXT_TAG_RESPONSE_HANDLER = 'Combodo\iTop\Service\WebRequestSender:ResponseHandler';

	/** @var string */
	const DEFAULT_SEND_MODE = self::ENUM_SEND_MODE_SYNC;
	/** @var int */
	const DEFAULT_CONNECTION_TIMEOUT_IN_SECONDS = 5;

	/** @var null|\Combodo\iTop\Service\WebRequestSender $oInstance */
	public static $oInstance = null;
	private static bool $mockDoPostRequest = false;

	public static function SetMockDoPostRequest(bool $mockDoPostRequest): void
	{
		static::$mockDoPostRequest = $mockDoPostRequest;
	}
	/**
	 * Return the singleton instance for this class
	 *
	 * @return \Combodo\iTop\Service\WebRequestSender
	 */
	public static function GetInstance()
	{
		if(static::$oInstance === null)
		{
			static::$oInstance = new static();
		}

		return static::$oInstance;
	}

	/**
	 * Send the $oRequest synchronously or asynchronously depending on the $bForcedSendMode parameter and the 'prefer_asynchronous' module parameter.
	 *
	 * @param \Combodo\iTop\Core\WebRequest $oRequest        The web request to send
	 * @param array                         $aIssues         Array of errors that occurred during sending
	 * @param null|\EventNotification       $oLog
	 * @param null|string                   $sForcedSendMode If null, will check module parameter, otherwise force mode using static::ENUM_SEND_MODE_SYNC or static::ENUM_SEND_MODE_ASYNC
	 *
	 * @return array An array containing the 'sender_status' (static::ENUM_SEND_STATE_XXX) and optionally 'response' a WebResponse object.
	 */
	public function Send(WebRequest $oRequest, &$aIssues, $oLog = null, $sForcedSendMode = null)
	{
		if($sForcedSendMode === static::ENUM_SEND_MODE_SYNC)
		{
			$aResult = $this->SendSynchronously($oRequest, $aIssues, $oLog);
		}
		elseif($sForcedSendMode === static::ENUM_SEND_MODE_ASYNC)
		{
			$aResult = $this->SendAsynchronously($oRequest, $aIssues, $oLog);
		}
		elseif(MetaModel::GetModuleSetting('combodo-webhook-integration', 'prefer_asynchronous', (static::DEFAULT_SEND_MODE === static::ENUM_SEND_MODE_ASYNC)))
		{
			$aResult = $this->SendAsynchronously($oRequest, $aIssues, $oLog);
		}
		else
		{
			$aResult = $this->SendSynchronously($oRequest, $aIssues, $oLog);
		}

		return $aResult;
	}

	/**
	 * Sends the $oRequest immediately, then returns the sender status code and the response object
	 *
	 * @param \Combodo\iTop\Core\WebRequest $oRequest WebRequest to send
	 * @param array                         $aIssues  Issue messages
	 * @param \EventNotification            $oLog
	 *
	 * @return array
	 */
	public function SendSynchronously(WebRequest $oRequest, &$aIssues, $oLog = null)
	{
		try
		{
			// ============================
			//  Inject proxy options
			// ============================
			// The proxy applies to every webhook unless the target host is listed in
			// 'no_proxy'. This allows a webhook reaching the Internet to keep using the
			// corporate proxy while another one targeting an internal host goes straight
			// to it: forwarding an internal destination through the proxy typically ends
			// up as an HTTP 503 returned by the proxy on the CONNECT.
			$oConfig    = MetaModel::GetConfig();
			$aProxyConf = $oConfig->GetModuleSetting('combodo-webhook-integration', 'proxy', null);

			$bBypassProxy = false;
			if (is_array($aProxyConf) && !empty($aProxyConf['no_proxy'])) {
				$sTargetHost  = parse_url($oRequest->GetURL(), PHP_URL_HOST);
				$bBypassProxy = self::HostMatchesNoProxy($sTargetHost, $aProxyConf['no_proxy']);
			}

			if (is_array($aProxyConf) && !empty($aProxyConf['host']) && !$bBypassProxy) {
				// Retrieve the current options of the request (start from an empty array if none)
				$aCurlOptions = $oRequest->GetOptions();
				if (!is_array($aCurlOptions)) {
					$aCurlOptions = array();
				}

				// Proxy host:port
				$aCurlOptions[CURLOPT_PROXY]     = $aProxyConf['host'];
				$aCurlOptions[CURLOPT_PROXYTYPE] = CURLPROXY_HTTP;

				// Optional authentication, if the proxy requires it
				if (!empty($aProxyConf['user'])) {
					$sAuth = $aProxyConf['user'];
					if (!empty($aProxyConf['password'])) {
						$sAuth .= ':'.$aProxyConf['password'];
					}
					$aCurlOptions[CURLOPT_PROXYUSERPWD] = $sAuth;
				}

				// Store the options back into the request
				$oRequest->SetOptions($aCurlOptions);
			}
			// ============================

			$aResponseHeaders = array();
			$sResponse = $this->DoPostRequest($oRequest->GetURL(), array(), null, $aResponseHeaders, $oRequest->GetOptions());

			$oResponse = new WebResponse();
			$oResponse->SetHeaders($aResponseHeaders)
				->SetBody($sResponse);

			// Log response
			if($oLog !== null)
			{
				$oLog->Set('response', $sResponse);
				$oLog->DBUpdate();
			}

			// Handle response
			if ($oRequest->HasResponseHandler()) {
				$oCtx = new ContextTag(static::CONTEXT_TAG_RESPONSE_HANDLER);
				call_user_func($oRequest->GetResponseHandlerName(), $oResponse, $oRequest->GetResponseHandlerParams());
				// Manual var unset to force context tag to be removed, otherwise it will be removed when the current method ends
				unset($oCtx);
			}

			return array(
				'sender_status' => static::ENUM_SEND_STATE_OK,
				'response' => $oResponse,
			);
		}
		catch(Exception $oException)
		{
			$sErrorMessage = 'Error while sending request to webhook: '.$oException->getMessage();
			IssueLog::Error($sErrorMessage);
			$aIssues[] = $sErrorMessage;

			return array(
				'sender_status' => static::ENUM_SEND_STATE_ERROR,
				'response' => null,
			);
		}
	}

	/**
	 * Add the $oRequest to the queue in order to be send later
	 *
	 * @param \Combodo\iTop\Core\WebRequest $oRequest WebRequest to add in the queue
	 * @param array                         $aIssues Issue messages
	 * @param null|\EventNotification       $oLog
	 *
	 * @return array
	 */
	public function SendAsynchronously(WebRequest $oRequest, &$aIssues, $oLog = null)
	{
		try
		{
			SendWebRequest::AddToQueue($oRequest, $oLog);
		}
		catch(Exception $oException)
		{
			$sErrorMessage = 'Exception thrown when trying to add request to queue: '.$oException->getMessage();
			IssueLog::Error($sErrorMessage);
			$aIssues[] = $sErrorMessage;

			return array(
				'sender_status' => static::ENUM_SEND_STATE_ERROR,
				'response' => null,
			);
		}

		return array(
			'sender_status' => static::ENUM_SEND_STATE_PENDING,
			'response' => null,
		);
	}

	/**
	 * Tells whether $sHost must bypass the proxy, according to the 'no_proxy' list of
	 * the module configuration. Same semantics as curl's no_proxy environment variable:
	 *
	 *   '*'                  -> everything bypasses the proxy
	 *   '.example.com'       -> matches the domain and any of its subdomains
	 *   'host.example.com'   -> exact match
	 *
	 * Comparison is case insensitive. When in doubt - empty host or malformed list -
	 * it returns false, i.e. the proxy IS applied, preserving the previous behaviour.
	 *
	 * @param string|null $sHost    Target host of the webhook
	 * @param mixed       $aNoProxy Exclusion list (array of strings)
	 *
	 * @return bool
	 * @since 1.4.10
	 */
	private static function HostMatchesNoProxy($sHost, $aNoProxy)
	{
		if (empty($sHost) || !is_array($aNoProxy)) {
			return false;
		}
		$sHost = strtolower(trim($sHost));

		foreach ($aNoProxy as $sEntry) {
			if (!is_string($sEntry)) {
				continue;
			}
			$sEntry = strtolower(trim($sEntry));
			if ($sEntry === '') {
				continue;
			}
			if ($sEntry === '*') {
				return true;
			}
			if ($sEntry[0] === '.') {
				// Suffix: '.example.com' also covers example.com itself
				$sBare = substr($sEntry, 1);
				if ($sHost === $sBare || substr($sHost, -strlen($sEntry)) === $sEntry) {
					return true;
				}
				continue;
			}
			if ($sHost === $sEntry) {
				return true;
			}
		}

		return false;
	}

	private function DoPostRequest($sUrl, $aData, $sOptionnalHeaders = null, &$aResponseHeaders = null, $aCurlOptions = array())
	{
		if (static::$mockDoPostRequest) {
			return '';
		} else {
			return utils::DoPostRequest($sUrl, $aData, $sOptionnalHeaders, $aResponseHeaders, $aCurlOptions);
		}
	}
}