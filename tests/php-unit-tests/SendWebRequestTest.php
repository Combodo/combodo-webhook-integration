<?php

namespace Combodo\iTop\Core\Test;

namespace Combodo\iTop\Core\Test;

use Combodo\iTop\Core\WebRequest;
use Combodo\iTop\Service\WebRequestSender;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use SendWebRequest;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class SendWebRequestTest extends ItopDataTestCase
{
	private WebRequestSender $oWebRequestSender;

	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('env-production/combodo-webhook-integration/vendor/autoload.php');

		$this->oWebRequestSender = $this->createMock(WebRequestSender::class);
		WebRequestSender::SetInstance($this->oWebRequestSender);
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		WebRequestSender::SetInstance(null);
	}

	public static function DoProcessProvider()
	{
		return [
			'cornercase error that should not happen' => [666, "Whoops! unexpected behavior (sender_status: 666)"],
			'sent ENUM_SEND_STATE_OK' => [0, 'Sent'],
			'sent ENUM_SEND_STATE_PENDING issue' => [1, 'Whoops! Seems like a bug occurred, the request should be sent in synchronous mode'],
		];
	}

	/**
	 * @dataProvider DoProcessProvider
	 */
	public function testDoProcessOK($iSenderStatus, $expected)
	{
		/** @var SendWebRequest $oSendWebRequest */
		$oWebRequest = new WebRequest('http://127.0.0.1');
		$aParams = [
			'request' => serialize($oWebRequest),
		];
		$oSendWebRequest = $this->GivenObject(SendWebRequest::class, $aParams);

		$aResult = [
			'sender_status' => $iSenderStatus,
		];

		$this->oWebRequestSender->expects(self::once())
			->method("Send")
		->willReturn($aResult);

		self::assertEquals($expected, $oSendWebRequest->DoProcess());
	}

	public function testDoProcessFailAndMakesureRetryWillBePossible()
	{
		/** @var SendWebRequest $oSendWebRequest */
		$oWebRequest = new WebRequest('http://127.0.0.1');
		$aParams = [
			'request' => serialize($oWebRequest),
		];
		$oSendWebRequest = $this->GivenObject(SendWebRequest::class, $aParams);

		$sExceptionThrownMsg = 'Error while sending request to webhook: BLABLA';
		$this->oWebRequestSender->expects(self::once())
			->method("Send")
			->will(
				$this->returnCallback(function ($oWebRequest, &$aIssues) use ($sExceptionThrownMsg) {
					$aIssues []= $sExceptionThrownMsg;
					return ['sender_status' => WebRequestSender::ENUM_SEND_STATE_ERROR ];
				}));

		$this->expectExceptionMessage("Failed: $sExceptionThrownMsg");
		$oSendWebRequest->DoProcess();
	}
}
