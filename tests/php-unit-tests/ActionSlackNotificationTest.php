<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
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
			"Large text with different tags" => [
				"input" => <<<HTML
<h1>Heading 1</h1><h2>Heading 2</h2><h3>Heading 3</h3><p><strong>Strong Text</strong><br><strong>Bold Text</strong><br><i>Emphasized Text</i><br><i>Italic Text</i><br><s>Deleted Text</s><br><s>Strikethrough Text</s><br> </p><ul><li>List Item 1</li><li>List Item 2</li></ul><p><code>Inline Code</code><br> </p><pre>Block of Code\r\nLine 1\r\nLine 2\r\n</pre>
HTML,
				"expected" => "*Heading 1*\n*Heading 2*\n*Heading 3*\n*Strong Text*\n*Bold Text*\n_Emphasized Text_\n_Italic Text_\n~Deleted Text~\n~Strikethrough Text~\n \n• List Item 1\n• List Item 2\n`Inline Code`\n \n```Block of Code\r\nLine 1\r\nLine 2\r\n```\n",
			],			
		];
	}
}
