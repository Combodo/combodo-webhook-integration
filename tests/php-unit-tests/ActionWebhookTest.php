<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\Test;

use ActioniTopWebhook;
use ActionWebhook;
use Closure;
use Combodo\iTop\Core\Notification\Action\Webhook\Exception\WebhookInvalidJsonValueException;
use Combodo\iTop\Core\WebResponse;
use Combodo\iTop\Oauth2Client\Service\Oauth2Service;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObject;
use EventNotification;
use MetaModel;
use RemoteApplicationType;
use RemoteiTopConnection;
use RemoteiTopConnectionToken;
use TriggerOnObjectCreate;
use TriggerOnObjectUpdate;
use utils;


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ActionWebhookTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;
	static Closure $oCallBackWebhook;
	protected function setUp(): void {
		parent::setUp();
	}

	protected function tearDown(): void {
		parent::tearDown();
	}

	public function testOnObjectUpdateCallbackShouldBeAllowedToModifyTriggeringObject()
	{
		$iPerson = $this->GivenObjectInDB('Person', ['first_name' => 'John', 'name' => 'Doe']);
		$oObject = MetaModel::GetObject('Person', $iPerson);

		$iTrigger = $this->GivenTriggerOnObjectUpdate($oObject);


		$this->GivenWebhookActionInvokingCallbackOnResponse($iTrigger, function (DBObject $oTriggeringObject, WebResponse $oWebReponse, ActionWebhook $oActionWebhook): void
		{
			$oTriggeringObject->Set('name', 'NameByCallBack');
		});

		$this->WhenObjectIsUpdatedOnOtherFieldThanName($oObject);

		$this->assertEquals('NameByCallBack', $oObject->Get('name'), 'The callback should have modified the object in memory');
		$oObject->Reload();
		$this->assertEquals('NameByCallBack', $oObject->Get('name'), 'The callback should have modified the object on the database');
	}

	public function testOnObjectCreateCallbackShouldBeAllowedToModifyTriggeringObject()
	{
		$oObject = MetaModel::NewObject('Person', ['first_name' => 'John', 'name' => 'Doe', ]);

		$iTrigger = $this->GivenTriggerOnObjectCreate($oObject);

		$this->GivenWebhookActionInvokingCallbackOnResponse($iTrigger, function (DBObject $oTriggeringObject, WebResponse $oWebReponse, ActionWebhook $oActionWebhook): void
		{
			$oTriggeringObject->Set('name', 'NameByCallBack');
			$oTriggeringObject->DBUpdate();
		});

		$this->WhenObjectIsCreatedWithoutName($oObject);

		$this->assertEquals('NameByCallBack', $oObject->Get('name'), 'The callback should have modified the object in memory');
		$oObject->Reload();
		$this->assertEquals('NameByCallBack', $oObject->Get('name'), 'The callback should have modified the object on the database');
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
		$oOauth2Service = $this->createMock(Oauth2Service::class);
		Oauth2Service::SetInstance($oOauth2Service);

		Oauth2Service::GetInstance()->InitByOauth2Client($oOauth2Client);
		$sAccessToken = Oauth2Service::GetInstance()->GetAccessToken();

		$oOauth2Service->expects($this->once())
			->method('InitByOauth2Client');

		$oOauth2Service->expects($this->once())
			->method('GetAccessToken')
			->willReturn('GABUZOMEU');

		list($oLog, $aHeaders) = $this->InvokePrepareHeaders($oRemoteApplication);
		$this->assertEquals(['Content-type: application/json', "Authorization: Bearer GABUZOMEU"], $aHeaders);
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

	public static function CallBackWebhook(DBObject $oTriggeringObject, WebResponse $oWebReponse, ActionWebhook $oActionWebhook): void
	{
		call_user_func(static::$oCallBackWebhook, $oTriggeringObject, $oWebReponse, $oActionWebhook);
	}

	public function GivenWebhookActionInvokingCallbackOnResponse(int $iTrigger, Closure $oCallBack): void
	{
		static::$oCallBackWebhook = $oCallBack;
		$iRemoteApplicationType = $this->GivenObjectInDB('RemoteApplicationType', [
			'name' => 'My connection type',
		]);

		$iRemoteApplicationConnection= $this->GivenObjectInDB('RemoteApplicationConnection', [
			'name' => 'Test localhost',
			'remoteapplicationtype_id' => $iRemoteApplicationType,
			'url' => '127.0.0.1',
		]);

		$this->GivenObjectInDB('ActionWebhook', [
			'asynchronous' => 'no',
			'name' => 'Test',
			'status' => 'test',
			'remoteapplicationconnection_id' => $iRemoteApplicationConnection,
			'test_remoteapplicationconnection_id' => $iRemoteApplicationConnection,
			'process_response_callback' => __CLASS__.'::CallBackWebhook',
			'payload' => '{}',
			'trigger_list' => ['trigger_id:' . $iTrigger],
		]);
	}

	public function GivenTriggerOnObjectUpdate(DBObject $object): int
	{
		return $this->GivenObjectInDB(TriggerOnObjectUpdate::class, [
			'description'  => 'My description',
			'target_class' => get_class($object)
		]);
	}

	public function GivenTriggerOnObjectCreate(DBObject $object): int
	{
		return $this->GivenObjectInDB(TriggerOnObjectCreate::class, [
			'description'  => 'My description',
			'target_class' => get_class($object)
		]);
	}

	public function WhenObjectIsUpdatedOnOtherFieldThanName(DBObject $oDBObject)
	{
		$oDBObject->Set('first_name', 'François');
		$oDBObject->DBUpdate();
	}

	public function WhenObjectIsCreatedWithoutName(DBObject $oDBObject)
	{
		$oDBObject->Set('org_id', $this->GetTestOrgId());
		$oDBObject->DBInsert();
	}
}
