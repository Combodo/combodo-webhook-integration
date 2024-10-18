<?php

namespace Combodo\iTop\Service;

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

}
