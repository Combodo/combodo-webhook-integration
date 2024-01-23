<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\CombodoWebhookIntegration;

use ActionSlackNotification;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;


class ActionSlackNotificationTest extends ItopDataTestCase
{
	/**
	 * @dataProvider TransformHTMLToSlackMarkupProvider
	 *
	 * @param string $sInput
	 * @param string $sExpected
	 *
	 * @return void
	 */
	public function testTransformHTMLToSlackMarkup(string $sInput, string $sExpected): void
	{
		$sTested = ActionSlackNotification::TransformHTMLToSlackMarkup($sInput);
		$this->assertEquals($sExpected, $sTested, "HTML not transform to Slack Markup as expected");
	}

	public function TransformHTMLToSlackMarkupProvider(): array
	{
		return [
			"Hyperlink" => [
				"input" => <<<HTML
<a href="https://github.com/Combodo/iTop" _target="blank">iTop Repository</a>
HTML,
				"expected" => "<https://github.com/Combodo/iTop|iTop Repository>",
			],
			"Mention old user ID with special chars encoded" => [
				"input" => <<<HTML
&lt;@Molkobain&gt;
HTML,
				"expected" => "<@Molkobain>",
			],
			"Mention new user ID with special chars encoded" => [
				"input" => <<<HTML
&lt;@U123456ABC&gt;
HTML,
				"expected" => "<@U123456ABC>",
			],
			"Mention new group ID with special chars encoded" => [
				"input" => <<<HTML
&lt;!SomeGroup&gt;
HTML,
				"expected" => "<!SomeGroup>",
			],
		];
	}
}
