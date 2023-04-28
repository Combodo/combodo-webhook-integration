<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Core\Notification\Action\_ActionWebhook;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

class _ActionWebhookTest extends ItopTestCase {
	protected function setUp(): void {
		parent::setUp();

		// no datamodel loaded so we need to include module file manually
		$this->RequireOnceItopFile('env-production/combodo-webhook-integration/src/Core/Notification/Action/_ActionWebhook.php');
	}

	/**
	 * @param ?string|boolean $sJson
	 * @param false|array $expected
	 *
	 * @return void
	 *
	 * @dataProvider GetArrayFromJsonStringProvider
	 */
	public function testGetArrayFromJsonString($sJson, $expected) {
		$result = _ActionWebhook::GetArrayFromJsonString($sJson);

		$this->assertSame($expected, $result);
	}

	public function GetArrayFromJsonStringProvider() {
		return [
			'false' => [false, false],
			'true' => [true, false],
			'false string' => ['false', false],
			'true string' => ['true', false],
			'null' => [null, false],
			'simple array' => ['{"foo":"bar"}', ['foo' => 'bar']],
		];
	}
}
