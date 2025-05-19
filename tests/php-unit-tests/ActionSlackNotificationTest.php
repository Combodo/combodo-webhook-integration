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
			"Simple p tags on different lines" => [
				"input" => <<<HTML
<p>hello</p><p>hi</p>
HTML,
				"expected" => "hello\nhi\n",
			],
			"Different tags on different lines" => [
				"input" => <<<HTML
<h2>hello</h2><p><strong>hi</strong></p>
HTML,
				"expected" => "*hello*\n*hi*\n",
			],
			"Nested tags" => [
				"input" => <<<HTML
<p><strong>I'm bold and</strong></p><p><i><strong>I'm bold and italic</strong></i></p>
HTML,
				"expected" => "*I'm bold and*\n_*I'm bold and italic*_\n",
			],
			"Large text with different tags" => [
				"input" => <<<HTML
<h1>Heading 1</h1><h2>Heading 2</h2><h3>Heading 3</h3><p><strong>Strong Text</strong><br><b>Bold Text</b><br><em>Emphasized Text</em><br><i>Italic Text</i><br><del>Deleted Text</del><br><s>Strikethrough Text</s><br><mark class="marker-yellow">Marked Text</mark><br><code>Inline Code</code></p><ul><li>List Item 1</li><li>List Item 2</li></ul><pre><code class="language-plaintext">Block of Code\r\nLine 1\r\nLine 2\r\n</code></pre>
HTML,
				"expected" => "*Heading 1*\n*Heading 2*\n*Heading 3*\n*Strong Text*\n*Bold Text*\n_Emphasized Text_\n_Italic Text_\n~Deleted Text~\n~Strikethrough Text~\n`Marked Text`\n`Inline Code`\n• List Item 1\n• List Item 2\n```Block of Code\r\nLine 1\r\nLine 2\r\n```\n",
			],
		];
	}
}
