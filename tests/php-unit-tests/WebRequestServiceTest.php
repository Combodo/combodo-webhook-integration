<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\Test;

use Combodo\iTop\Service\WebRequestService;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class WebRequestServiceTest extends ItopDataTestCase {
	protected function setUp(): void {
		parent::setUp();
	}

	public function testObfuscateRawHeader() {
		$sAdditionalHeaders = <<<TXT
Authorization: TOTO1
Auth-Token: TOTO4
Authorization:TOTO2
Auth-Token:TOTO3
TXT;

		$sExpected = <<<TXT
Authorization: ******
Auth-Token: ******
Authorization: ******
Auth-Token: ******
TXT;

		$this->assertEquals($sExpected, WebRequestService::GetInstance()->ObfuscateRawHeader($sAdditionalHeaders));
	}
}
