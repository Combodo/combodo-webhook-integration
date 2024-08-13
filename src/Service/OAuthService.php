<?php

namespace Combodo\iTop\Service;

use \OAuthClient;
class OAuthService {
	/** @var null|OAuthService $oInstance */
	public static $oInstance = null;

	public static function GetInstance() : ?OAuthService
	{
		if(is_null(static::$oInstance))
		{
			static::$oInstance = new static();
		}

		return static::$oInstance;
	}

	public function PrepareHeaders(int $oAuthClientId, array $aHeaders) : array {
		return [];
	}

	/*public static function getProviderForIMAP($oMailbox)
	{
		$oOAuthClient = MetaModel::GetObject('OAuthClient', $oMailbox->Get('oauth_client_id'));
		return OAuthClientProviderFactory::GetClientProvider($oOAuthClient);
	}*/
}
