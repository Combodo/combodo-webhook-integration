<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Core\Notification\Action\_ActionWebhook;
use Combodo\iTop\Core\Notification\Action\Webhook\Exception\WebhookInvalidJsonValueException;
use Combodo\iTop\Core\WebResponse;
use ActionWebhook;
use Combodo\iTop\Service\CallbackService;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DBObject;
use Exception;
use ReflectionException;

class _ActionWebhookTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('env-production/combodo-webhook-integration/vendor/autoload.php');
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
	 * @throws ReflectionException
	 * @throws Exception
	 */
	public function testCheckCallbackSignature(string $sResponseCallback, $bExpectedException, $sErrorMessage)
	{
		if ($bExpectedException) {
			$this->expectException(\SecurityException::class);
			$this->expectExceptionMessage($sErrorMessage);
		} else {
			$this->expectNotToPerformAssertions();
		}
		$oCallBackService = new CallbackService($sResponseCallback);
		if ($oCallBackService->IsStatic()) {
			$oCallBackService->CheckCallbackSignature(DBObject::class, ['Combodo\iTop\Core\WebResponse', 'ActionWebhook']);
		} else {
			$oCallBackService->CheckCallbackSignature($this::class, ['Combodo\iTop\Core\WebResponse', 'ActionWebhook']);
		}
	}

	public function CheckCallbackSignatureTestProvider(): array
	{
		return [
			'Check callback signature with no parameters'                              => [
				'sResponseCallback' => _ActionWebhookTest::class.'::CallBackWebhookNoParams',
				'expectedException' => true,
				'sErrorMessage'     => 'The callback method \'CallBackWebhookNoParams\' of class \'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest\' must have exactly 3 parameters.',
			],
			'Check callback signature with no parameters on object'                    => [
				'sResponseCallback' => '$this->CallBackWebhookNoParamsOnObject',
				'expectedException' => true,
				'sErrorMessage'     => 'The callback method \'CallBackWebhookNoParamsOnObject\' of class \'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest\' must have exactly 2 parameters.',
			],
			'Check callback signature with one parameter'                              => [
				'sResponseCallback' => _ActionWebhookTest::class.'::CallBackWebhookOneParam',
				'expectedException' => true,
				'sErrorMessage'     => 'The callback method \'CallBackWebhookOneParam\' of class \'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest\' must have exactly 3 parameters.',
			],
			'Check callback signature with one parameter on object'                    => [
				'sResponseCallback' => '$this->CallBackWebhookOneParamOnObject',
				'expectedException' => true,
				'sErrorMessage'     => 'The callback method \'CallBackWebhookOneParamOnObject\' of class \'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest\' must have exactly 2 parameters.',
			],
			'Check callback signature with no type'                                    => [
				'sResponseCallback' => _ActionWebhookTest::class.'::CallBackWebhookThreeUntypedParams',
				'expectedException' => true,
				'sErrorMessage'     => "The callback method 'CallBackWebhookThreeUntypedParams' of class 'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest' must have type-hinted parameters, but parameter oDBObject is not type-hinted.",
			],
			'Check callback signature with no type on object'                          => [
				'sResponseCallback' => '$this->CallBackWebhookTwoUntypedParamsOnObject',
				'expectedException' => true,
				'sErrorMessage'     => "The callback method 'CallBackWebhookTwoUntypedParamsOnObject' of class 'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest' must have type-hinted parameters, but parameter oWebResponse is not type-hinted.",
			],
			'Check callback signature with wrong type'                                 => [
				'sResponseCallback' => _ActionWebhookTest::class.'::CallBackWebhookThreeParamsWithWrongType',
				'expectedException' => true,
				'sErrorMessage'     => "The callback method 'CallBackWebhookThreeParamsWithWrongType' of class 'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest' must have a parameter of type 'DBObject', but it has int instead.",
			],
			'Check callback signature with wrong type on object'                       => [
				'sResponseCallback' => '$this->CallBackWebhookTwoParamsWithWrongTypeOnObject',
				'expectedException' => true,
				'sErrorMessage'     => "The callback method 'CallBackWebhookTwoParamsWithWrongTypeOnObject' of class 'Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest' must have a parameter of type 'Combodo\iTop\Core\WebResponse', but it has int instead.",
			],
			'Check callback signature with correct type but incorrect order'           => [
				'sResponseCallback' => _ActionWebhookTest::class.'::CallBackWebhookThreeParamsCorrectButWrongOrder',
				'expectedException' => true,
				'sErrorMessage'     => "Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest' must have a parameter of type 'DBObject', but it has Combodo\iTop\Core\WebResponse instead.",
			],
			'Check callback signature with correct type but incorrect order on object' => [
				'sResponseCallback' => '$this->CallBackWebhookTwoParamsCorrectButWrongOrderOnObject',
				'expectedException' => true,
				'sErrorMessage'     => "Combodo\iTop\Test\UnitTest\Core\_ActionWebhookTest' must have a parameter of type 'Combodo\iTop\Core\WebResponse', but it has ActionWebhook instead.",
			],
			'Check callback signature with correct type'                               => [
				'sResponseCallback' => _ActionWebhookTest::class.'::CallBackWebhookThreeParamsCorrect',
				'expectedException' => false,
				'sErrorMessage'     => '',
			],
			'Check callback signature with correct type on object'                     => [
				'sResponseCallback' => '$this->CallBackWebhookTwoParamsCorrectOnObject',
				'expectedException' => false,
				'sErrorMessage'     => '',
			],
			'Check callback signature with correct type and namespace'                 => [
				'sResponseCallback' => _ActionWebhookTest::class.'::CallBackWebhookThreeParamsCorrectWithNamespace',
				'expectedException' => false,
				'sErrorMessage'     => '',
			],
			'Check callback signature with correct type and namespace on object'       => [
				'sResponseCallback' => '$this->CallBackWebhookTwoParamsCorrectWithNamespaceOnObject',
				'expectedException' => false,
				'sErrorMessage'     => '',
			],
		];
	}

	public static function CallBackWebhookNoParams()
	{
		// This method is intentionally left empty to test the callback signature
	}

	public static function CallBackWebhookOneParam(int $i)
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

	public static function CallBackWebhookThreeParamsCorrectButWrongOrder(WebResponse $oWebResponse, DBObject $oDBObject, ActionWebhook $oActionWebhook)
	{
		// This method is intentionally left empty to test the callback signature
	}

	public static function CallBackWebhookThreeParamsCorrect(DBObject $oDBObject, WebResponse $oWebResponse, ActionWebhook $oActionWebhook)
	{
		// This method is intentionally left empty to test the callback signature
	}

	public static function CallBackWebhookThreeParamsCorrectWithNamespace(\DBObject $oDBObject, \Combodo\iTop\Core\WebResponse $oWebResponse, \ActionWebhook $oActionWebhook)
	{
		// This method is intentionally left empty to test the callback signature
	}

	private function CallBackWebhookNoParamsOnObject()
	{
		// This method is intentionally left empty to test the callback signature
	}

	private function CallBackWebhookOneParamOnObject(int $i)
	{
		// This method is intentionally left empty to test the callback signature
	}

	private function CallBackWebhookTwoUntypedParamsOnObject($oWebResponse, $oActionWebhook)
	{
		// This method is intentionally left empty to test the callback signature
	}

	private function CallBackWebhookTwoParamsWithWrongTypeOnObject(int $oWebResponse, $oActionWebhook)
	{
		// This method is intentionally left empty to test the callback signature
	}

	private function CallBackWebhookTwoParamsCorrectButWrongOrderOnObject(ActionWebhook $oActionWebhook, WebResponse $oWebResponse)
	{
		// This method is intentionally left empty to test the callback signature
	}

	private function CallBackWebhookTwoParamsCorrectOnObject( WebResponse $oWebResponse, ActionWebhook $oActionWebhook)
	{
		// This method is intentionally left empty to test the callback signature
	}

	private function CallBackWebhookTwoParamsCorrectWithNamespaceOnObject(\Combodo\iTop\Core\WebResponse $oWebResponse, \ActionWebhook $oActionWebhook)
	{
		// This method is intentionally left empty to test the callback signature
	}
}
