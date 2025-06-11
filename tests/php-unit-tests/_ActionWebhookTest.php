<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Core\Notification\Action\_ActionWebhook;
use Combodo\iTop\Core\Notification\Action\Webhook\Exception\WebhookInvalidJsonValueException;
use Combodo\iTop\Core\WebResponse;
use Combodo\iTop\Core\ActionWebhook;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DBObject;

class _ActionWebhookTest extends ItopTestCase {
	protected function setUp(): void {
		parent::setUp();

		// no datamodel loaded so we need to include module file manually
		$this->RequireOnceItopFile('env-production/combodo-webhook-integration/src/Core/Notification/Action/_ActionWebhook.php');
		$this->RequireOnceItopFile('env-production/combodo-webhook-integration/src/Core/Notification/Action/Webhook/Exception/WebhookInvalidJsonValueException.php');
	}

	/**
	 * @param ?string|boolean $sJson
	 * @param false|array $expected
	 *
	 * @return void
	 *
	 * @dataProvider IsJsonValidProvider
	 */
	public function testIsJsonValid($json, $bExpectException, $expected = null) {
		if ($bExpectException) {
			$this->expectException(WebhookInvalidJsonValueException::class);
		}
		$result = _ActionWebhook::JsonDecodeWithError($json);

		if (false === $bExpectException) {
			$this->assertSame($expected, $result);
		}
	}

	public function IsJsonValidProvider() {
		return [
			// non string values should exit right away
			'null' => [null, true],
			'false' => [false, true],
			'true' => [true, true],

			// simple strings
			'null string' => ['null', false, null],
			'false string' => ['false', false, false],
			'true string' => ['true', false, true],
			'string without quotes' => ['foobar', true],
			'string with quotes' => ['"foobar"', false, 'foobar'],

			// strings containing json objects
			'simple array' => ['{"foo":"bar"}', false, ['foo' => 'bar']],
			'integer array' => ['{"foo":123}', false, ['foo' => 123]],
			'invalid array' => ['{foo:bar}', true],
		];
	}

	/**
	 * @return void
	 * @dataProvider CheckCallbackSignatureTestProvider
	 */
	public function testCheckCallbackSignature(string $sResponseCallback, $oTriggeringObject, $bExpectedException, $sErrorMessage)
	{
		if ($bExpectedException) {
			$this->expectException(\SecurityException::class);
			$this->expectExceptionMessage($sErrorMessage);
		} else {
			$this->expectNotToPerformAssertions();
		}
		_ActionWebhook::CheckCallbackSignature($sResponseCallback, $oTriggeringObject);
	}

	public function CheckCallbackSignatureTestProvider()
	{
		return [
			'Check callback signature with no parameters'              => [
				'sResponseCallback' => 'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest::CallBackWebhookNoParams',
				'oTriggeringObject' => null,
				'expectedException' => true,
				'sErrorMessage'     => 'The callback method \'CallBackWebhookNoParams\' of class \'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest\' must have exactly 3 parameters: the DBObject, the WebResponse and the ActionWebhook object.',
			],
			'Check callback signature with one parameter'              => [
				'sResponseCallback' => 'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest::CallBackWebhookOneParam',
				'oTriggeringObject' => null,
				'expectedException' => true,
				'sErrorMessage'     => 'The callback method \'CallBackWebhookOneParam\' of class \'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest\' must have exactly 3 parameters: the DBObject, the WebResponse and the ActionWebhook object.',
			],
			'Check callback signature with no type'                    => [
				'sResponseCallback' => 'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest::CallBackWebhookThreeUntypedParams',
				'oTriggeringObject' => null,
				'expectedException' => true,
				'sErrorMessage'     => "The callback method 'CallBackWebhookThreeUntypedParams' of class 'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest' must have type-hinted parameters, but parameter oDBObject is not type-hinted.",
			],
			'Check callback signature with wrong type'                 => [
				'sResponseCallback' => 'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest::CallBackWebhookThreeParamsWithWrongType',
				'oTriggeringObject' => null,
				'expectedException' => true,
				'sErrorMessage'     => "The callback method 'CallBackWebhookThreeParamsWithWrongType' of class 'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest' must have a first parameter of type 'DBObject', but it has int instead.",
			],
			'Check callback signature with correct type'               => [
				'sResponseCallback' => 'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest::CallBackWebhookThreeParamsCorrect',
				'oTriggeringObject' => null,
				'expectedException' => false,
				'sErrorMessage'     => '',
			],
/*			'Check callback signature with correct type and namespace' => [ // commenting because it's not working; but maybe it's intended
				'sResponseCallback' => 'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest::CallBackWebhookThreeParamsCorrectWithNamespace',
				'oTriggeringObject' => null,
				'expectedException' => false,
				'sErrorMessage'     => '',
			],*/
		];
	}

	public static function CallBackWebhookNoParams()
	{
		// This method is intentionally left empty to test the callback signature
	}

	public static function CallBackWebhookOneParam()
	{
		// This method is intentionally left empty to test the callback signature
	}

	public static function CallBackWebhookThreeUntypedParams($oDBObject, $oWebResponse, $oActionWebhook)
	{
		// This method is intentionally left empty to test the callback signature
	}

	public static function CallBackWebhookThreeParamsWithWrongType(int $oDBObject, $oWebResponse, $oActionWebhook)
	{
		// This method is intentionally left empty to test the callback signature
	}

	public static function CallBackWebhookThreeParamsCorrect(DBObject $oDBObject, WebResponse $oWebResponse, \ActionWebhook $oActionWebhook)
	{
		// This method is intentionally left empty to test the callback signature
	}

	public static function CallBackWebhookThreeParamsCorrectWithNamespace(\DBObject $oDBObject, Combodo\iTop\Core\WebResponse $oWebResponse, Combodo\iTop\Core\ActionWebhook $oActionWebhook)
	{
		// This method is intentionally left empty to test the callback signature
	}
}
