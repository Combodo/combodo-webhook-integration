<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\Test;

use ActioniTopWebhook;
use ActionWebhook;
use Combodo\iTop\Core\Notification\Action\Webhook\Exception\WebhookInvalidJsonValueException;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use EventNotification;
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

	public function testPrepareHeaderWithUserAndPassword()
	{
		$oRemoteApplicationType = new RemoteApplicationType();
		$oRemoteApplicationType->Set('name', 'iTop');
		$oRemoteApplicationType->DBWrite();

		// iTop connexion via username / password
		$oRemoteApplication = new RemoteiTopConnection();
		$oRemoteApplication->Set('name', 'Test iTop');
		$oRemoteApplication->Set('remoteapplicationtype_id', $oRemoteApplicationType->GetKey());
		$oRemoteApplication->Set('url', 'https://www.combodo.com');
		$oRemoteApplication->Set('auth_user', 'administrator');
		$oRemoteApplication->Set('auth_pwd', 'Adm1nistrator++!!');
		$oRemoteApplication->DBWrite();

		$oAction = new ActioniTopWebhook();
		$oAction->Set('remoteapplicationconnection_id', $oRemoteApplication->GetKey());
		$oAction->Set('test_remoteapplicationconnection_id', $oRemoteApplication->GetKey());
		$oAction->Set('name', 'test');
		$oAction->DBWrite();

		$oLog = new EventNotification();

		$aHeaders = $this->InvokeNonPublicMethod(get_class($oAction), 'PrepareHeaders', $oAction, [[], &$oLog]);
		$this->assertEquals(['Content-type: application/json', 'Authorization: Basic YWRtaW5pc3RyYXRvcjpBZG0xbmlzdHJhdG9yKyshIQ=='], $aHeaders);

	}

	public function testPrepareHeaderWithToken()
	{
		$oRemoteApplicationType = new RemoteApplicationType();
		$oRemoteApplicationType->Set('name', 'iTop');
		$oRemoteApplicationType->DBWrite();

		// iTop connexion via a token
		$oRemoteApplicationToken = new RemoteiTopConnectionToken();
		$oRemoteApplicationToken->Set('name', 'Test iTop');
		$oRemoteApplicationToken->Set('remoteapplicationtype_id', $oRemoteApplicationType->GetKey());
		$oRemoteApplicationToken->Set('url', 'https://www.combodo.com');
		$oRemoteApplicationToken->Set('token', 'HAhq2Zfyr24ge!/jqsdf)sCf45A');
		$oRemoteApplicationToken->DBWrite();

		$oAction = new ActioniTopWebhook();
		$oAction->Set('remoteapplicationconnection_id', $oRemoteApplicationToken->GetKey());
		$oAction->Set('test_remoteapplicationconnection_id', $oRemoteApplicationToken->GetKey());
		$oAction->Set('name', 'test2');
		$oAction->DBWrite();

		$oLog = new EventNotification();

		$aHeaders = $this->InvokeNonPublicMethod(get_class($oAction), 'PrepareHeaders', $oAction, [[], &$oLog]);
		$this->assertEquals(['Content-type: application/json', 'Auth-Token: HAhq2Zfyr24ge!/jqsdf)sCf45A'], $aHeaders);

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
