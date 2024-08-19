<?php

namespace Combodo\iTop\Service;

use \OAuthClient;
use \MetaModel;
use \IssueLog;
use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderAbstract;
use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderFactory;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class OAuthService {
	const LOG_CHANNEL = 'OAuth';

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

	public function GetAccessToken(int $oAuthClientId) : string
	{
		/** @var OAuthClient $oOAuthClient */
		$oOAuthClient = MetaModel::GetObject('OAuthClient', $oAuthClientId);
		/** @var OAuthClientProviderAbstract $oProvider */
		$oProvider = OAuthClientProviderFactory::GetClientProvider($oOAuthClient);

		if (empty($oProvider->GetAccessToken()))
		{
			IssueLog::Error('No prior authentication to OAuth', static::LOG_CHANNEL, [ 'authClientId' => $oAuthClientId, 'vendorName' => $oProvider::GetVendorName() ]);
			throw new IdentityProviderException('No prior authentication to OAuth', 255);
		}

		$oProvider->SetAccessToken($oProvider->GetVendorProvider()->getAccessToken('refresh_token',
		[
			'refresh_token' => $oProvider->GetAccessToken()->getRefreshToken(),
			'scope' => $oProvider->GetScope()
		]));

		$sAccessToken = $oProvider->GetAccessToken()->getToken();
		if (empty($sAccessToken))
		{
			IssueLog::Error('No OAuth token', static::LOG_CHANNEL, [ 'authClientId' => $oAuthClientId, 'vendorName' => $oProvider::GetVendorName() ]);
			throw new IdentityProviderException('No OAuth token', 255);
		}

		IssueLog::Debug("Oauth XOAUTH2 auth=Bearer $sAccessToken", static::LOG_CHANNEL, [ 'authClientId' => $oAuthClientId, 'vendorName' => $oProvider::GetVendorName(), 'accessToken' => $sAccessToken ]);
		return $sAccessToken;
	}
}
