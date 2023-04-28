<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use ActionWebhook;
use Combodo\iTop\Core\Notification\Action\Webhook\Exception\WebhookInvalidJsonValueException;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * {@see ActionWebhook} is defined is XML and so compiled in the big model.php file, with lots of dependencies on the datamodel.
 * So in order to include the class we unfortunately need to load the whole datamodel and use {@see ItopDataTestCase}:(
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ActionWebhookTest extends ItopDataTestCase {
	/**
	 * @dataProvider ApplyParamsToJsonProvider
	 */
	public function testApplyParamsToJson($json, bool $bExpectException, $expected = null, array $aContextArgs = []) {
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

	public function ApplyParamsToJsonProvider() : array {
		return [
			'String Param not replaced' => ['$this->value$', true],
			'String Param replaced' => ['$this->value$', false, '"toto"', ['this->value' => '"toto"']],

			'Array quotes in value Param not replaced' => ['{"value":$this->value$}', true],
			'Array quotes in value Param replaced' => ['{"value":$this->value$}', false, '{"value":"toto"}', ['this->value' => '"toto"']],

			'Array quotes in json Param not replaced' => ['{"value":"$this->value$"}', false, '{"value":"$this->value$"}', []],
			'Array quotes in json Param replaced' => ['{"value":"$this->value$"}', false, '{"value":"toto"}', ['this->value' => 'toto']],
		];
	}
}
