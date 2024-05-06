<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Core\Notification\Action\_ActionWebhook;
use Combodo\iTop\Core\Notification\Action\Webhook\Exception\WebhookInvalidJsonValueException;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

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
}
