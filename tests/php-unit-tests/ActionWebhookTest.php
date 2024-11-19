<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\Test;

use ActioniTopWebhook;
use ActionWebhook;
use Combodo\iTop\Core\Notification\Action\Webhook\Exception\WebhookInvalidJsonValueException;
use Combodo\iTop\Oauth2Client\Service\HybridAuthService;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use EventNotification;
use Hybridauth\Adapter\OAuth2;
use RemoteApplicationType;
use RemoteiTopConnection;
use RemoteiTopConnectionToken;


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ActionWebhookTest extends ItopDataTestCase
{
	protected function setUp(): void {
		parent::setUp();
	}

	protected function tearDown(): void {
		parent::tearDown();
		HybridAuthService::SetInstance(null);
	}

	public function testPrepareHeaderWithUserAndPassword()
	{
		$oRemoteApplicationType = $this->GetApplicationType();
		$oRemoteApplication = $this->createObject(RemoteiTopConnection::class,
			[
				'name' => 'Test iTop',
				'remoteapplicationtype_id' => $oRemoteApplicationType->GetKey(),
				'url' => 'https://www.combodo.com',
				'auth_user' => 'administrator',
				'auth_pwd' => 'Adm1nistrator++!!',
			]
		);

		list($oLog, $aHeaders) = $this->InvokePrepareHeaders($oRemoteApplication);
		$this->assertEquals(['Content-type: application/json', 'Authorization: Basic YWRtaW5pc3RyYXRvcjpBZG0xbmlzdHJhdG9yKyshIQ=='], $aHeaders);

	}

	public function testPrepareHeaderWithToken()
	{
		$oRemoteApplicationType = $this->GetApplicationType();

		// iTop connexion via a token
		$oRemoteApplicationToken = new RemoteiTopConnectionToken();
		$oRemoteApplicationToken->Set('name', 'Test iTop');
		$oRemoteApplicationToken->Set('remoteapplicationtype_id', $oRemoteApplicationType->GetKey());
		$oRemoteApplicationToken->Set('url', 'https://www.combodo.com');
		$oRemoteApplicationToken->Set('token', 'HAhq2Zfyr24ge!/jqsdf)sCf45A');
		$oRemoteApplicationToken->DBWrite();

		list($oLog, $aHeaders) = $this->InvokePrepareHeaders($oRemoteApplicationToken);
		$this->assertEquals(['Content-type: application/json', 'Auth-Token: HAhq2Zfyr24ge!/jqsdf)sCf45A'], $aHeaders);
	}

	public function testPrepareHeaderWithOauth2()
	{
		$oRemoteApplicationType = $this->GetApplicationType();

		/** @var \Oauth2Client $oOauth2Client */
		$oOauth2Client = $this->createObject(\GitHubOauth2Client::class,
			[
				'name' => 'webhook',
				'client_id' => 'client_123',
				'client_secret' => 'secret456',
				'scope' => 'toto',
				'access_token' => 'access_token1',
				'token_type' => 'Bearer',
				'refresh_token' => 'refresh_token1',
				'access_token_expiration' => '2024-11-13 00:37:48',
			]
		);

		$oRemoteApplication = $this->createObject(\RemoteiTopConnectionOauth2::class,
			[
				'name' => 'Test iTop',
				'remoteapplicationtype_id' => $oRemoteApplicationType->GetKey(),
				'url' => 'https://www.combodo.com',
				'oauth2client_id' => $oOauth2Client->GetKey(),
			]
		);

		//avoid calling Github IDP
		$oHybridAuthService = $this->createMock(HybridAuthService::class);
		HybridAuthService::SetInstance($oHybridAuthService);
		$oAuth2 = $this->createMock(OAuth2::class);
		$oHybridAuthService->expects($this->once())
			->method('GetOauth2')
			->willReturn($oAuth2);

		$oAuth2->expects($this->once())
			->method('isConnected')
			->willReturn(true);

		list($oLog, $aHeaders) = $this->InvokePrepareHeaders($oRemoteApplication);
		$this->assertEquals(['Content-type: application/json', "Authorization: Bearer access_token1"], $aHeaders);
	}

	public function InvokePrepareHeaders($oRemoteApplication): array {
		$oAction = $this->createObject(ActioniTopWebhook::class,
			[
				'remoteapplicationconnection_id' => $oRemoteApplication->GetKey(),
				'test_remoteapplicationconnection_id' => $oRemoteApplication->GetKey(),
				'name' => 'test',
			]
		);

		$oLog = new EventNotification();

		$aHeaders = $this->InvokeNonPublicMethod(get_class($oAction), 'PrepareHeaders', $oAction, [[], &$oLog]);

		return array($oLog, $aHeaders);
	}

	public function GetApplicationType() : RemoteApplicationType {
		$oRemoteApplicationType = $this->createObject(RemoteApplicationType::class, ['name' => 'iTop']);

		return $oRemoteApplicationType;
	}

	/**
	 * @dataProvider ApplyParamsToJsonProvider
	 */
	public function testApplyParamsToJson($json, bool $bExpectException, $expected = null, array $aContextArgs = [])
	{
		if ($bExpectException) {
			$this->expectException(WebhookInvalidJsonValueException::class);
		}

		$oActionWebhook = new ActionWebhook();
		$result = $this->InvokeNonPublicMethod(ActionWebhook::class, 'ApplyParamsToJson', $oActionWebhook,
			[$aContextArgs, $json]);

		if (false === $bExpectException) {
			$this->assertSame($expected, $result);
		}
	}

	public function ApplyParamsToJsonProvider(): array
	{
		return [
			'String Param not replaced' => ['$this->value$', true],
			'String Param replaced' => ['$this->value$', false, '"toto"', ['this->value' => '"toto"']],

			'Array value without quotes Param not replaced' => ['{"value":$this->value$}', true],
			'Array value without quotes Param replaced with not quoted string' => ['{"value":$this->value$}', true, null, ['this->value' => 'toto']],
			'Array value without quotes Param replaced with quoted string' => ['{"value":$this->value$}', false, '{"value":"toto"}', ['this->value' => '"toto"']],
			'Array value without quotes Param replaced with numeric value' => ['{"value":$this->value$}', false, '{"value":2}', ['this->value' => 2]],

			'Array value with quotes Param not replaced' => ['{"value":"$this->value$"}', false, '{"value":"$this->value$"}', []],
			'Array value with quotes Param replaced' => ['{"value":"$this->value$"}', false, '{"value":"toto"}', ['this->value' => 'toto']],
		];
	}

	/**
	 * @dataProvider GetContentTypeProvider
	 */
	public function testGetContentType($sInput, $expectedReturnedContentType)
	{
		$aHeaders = [$sInput];

		$oActionWebhook = new ActionWebhook();
		$result = $this->InvokeNonPublicMethod(ActionWebhook::class, 'GetContentType', $oActionWebhook,
			[$aHeaders]);

		$this->assertSame($expectedReturnedContentType, $result);
	}

	public function GetContentTypeProvider()
	{
		return [
			'null' => [null, null],
			'empty string' => ['', null],

			'typo in header' => ['ContentTypo:application/json', null],

			'correct header JSON' => ['Content-type:application/json', 'application/json'],
			'correct header JSON with spaces and tab' => ['Content-type   :		   application/json', 'application/json'],

			'header no value' => ['Content-type:', null],
			'header value only spaces and tab' => ['Content-type:   	    ', null],
		];
	}
}
