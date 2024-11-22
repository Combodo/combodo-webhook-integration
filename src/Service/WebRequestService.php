<?php

namespace Combodo\iTop\Service;

use Combodo\iTop\Oauth2Client\Service\Oauth2Service;
use RemoteiTopConnectionOauth2;

class WebRequestService {
	private static WebRequestService $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): WebRequestService
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new static();
		}

		return static::$oInstance;
	}

	/**
	 * Test purpose only
	 */
	final public static function SetInstance(WebRequestService $oInstance)
	{
		self::$oInstance = $oInstance;
	}

	/**
	 * Test purpose only
	 */
	final public static function ResetInstance()
	{
		self::$oInstance = new static();
	}

	public function ObfuscateRawHeader(string $sRawHeaders) : string {
		// Obfuscate authorization header
		$aReplaceRegexps = [
			'/^Authorization:(.+)$/m' => 'Authorization: ******',
			'/^Auth-Token:(.+)$/m' => 'Auth-Token: ******',
		];

		$sObfuscatedRawHeaders = $sRawHeaders;
		foreach($aReplaceRegexps as $sRegExp => $sAuthHeaderObfuscated){
			$sObfuscatedRawHeaders = preg_replace($sRegExp, $sAuthHeaderObfuscated, $sObfuscatedRawHeaders);
		}

		return $sObfuscatedRawHeaders;
	}

	public function EnrichHeader(RemoteiTopConnectionOauth2 $oRemoteiTopConnectionOauth2, &$aHeaders) : void {
		/** @var \OAuth2Client $oOauth2Client */
		$oOauth2Client = \MetaModel::GetObject(\Oauth2Client::class, $oRemoteiTopConnectionOauth2->Get('oauth2client_id'));

		$sAccessToken = Oauth2Service::GetInstance()->GetAccessTokenByOauth2Client($oOauth2Client);
		$aHeaders[] = sprintf("Authorization: %s %s",
			$oOauth2Client->Get('token_type'), $sAccessToken
		);
	}
}
