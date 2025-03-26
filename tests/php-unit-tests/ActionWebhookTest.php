<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\Test;

use ActioniTopWebhook;
use ActionWebhook;
use Closure;
use Combodo\iTop\Core\Notification\Action\Webhook\Exception\WebhookInvalidJsonValueException;
use Combodo\iTop\Core\WebResponse;
use Combodo\iTop\Oauth2Client\Service\Oauth2Service;
use Combodo\iTop\Service\WebRequestSender;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObject;
use EventNotification;
use MetaModel;
use RemoteApplicationType;
use RemoteiTopConnection;
use RemoteiTopConnectionToken;
use TriggerOnObjectCreate;
use TriggerOnObjectUpdate;
use utils;


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ActionWebhookTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;
	static Closure $oCallBackWebhook;
	protected function setUp(): void {
		parent::setUp();
	}

	protected function tearDown(): void {
		parent::tearDown();
	}

	public function testOnObjectUpdateCallbackShouldBeAllowedToModifyTriggeringObject()
	{
		WebRequestSender::SetMockDoPostRequest(true);
		$iPerson = $this->GivenObjectInDB('Person', ['first_name' => 'John', 'name' => 'Doe']);
		$oObject = MetaModel::GetObject('Person', $iPerson);

		$iTrigger = $this->GivenTriggerOnObjectUpdate($oObject);


		$this->GivenWebhookActionInvokingCallbackOnResponse($iTrigger, function (DBObject $oTriggeringObject, WebResponse $oWebReponse, ActionWebhook $oActionWebhook): void
		{
			$oTriggeringObject->Set('name', 'NameByCallBack');
		});

		$this->WhenObjectIsUpdatedOnOtherFieldThanName($oObject);

		$this->assertEquals('NameByCallBack', $oObject->Get('name'), 'The callback should have modified the object in memory');
		$oObject->Reload();
		$this->assertEquals('NameByCallBack', $oObject->Get('name'), 'The callback should have modified the object on the database');
	}

	public function testOnObjectCreateCallbackShouldBeAllowedToModifyTriggeringObject()
	{
		WebRequestSender::SetMockDoPostRequest(true);
		$oObject = MetaModel::NewObject('Person', ['first_name' => 'John', 'name' => 'Doe', ]);

		$iTrigger = $this->GivenTriggerOnObjectCreate($oObject);

		$this->GivenWebhookActionInvokingCallbackOnResponse($iTrigger, function (DBObject $oTriggeringObject, WebResponse $oWebReponse, ActionWebhook $oActionWebhook): void
		{
			$oTriggeringObject->Set('name', 'NameByCallBack');
			$oTriggeringObject->DBUpdate();
		});

		$this->WhenObjectIsInsertedWithoutNameChange($oObject);

		$this->assertEquals('NameByCallBack', $oObject->Get('name'), 'The callback should have modified the object in memory');
		$oObject->Reload();
		$this->assertEquals('NameByCallBack', $oObject->Get('name'), 'The callback should have modified the object on the database');
	}

	public function testPrepareHeaderWithUserAndPassword()
	{
		$oRemoteApplicationType = $this->GetApplicationType();
		$oRemoteApplication = $this->createObject(RemoteiTopConnection::class,
			[
				'name' => 'Test iTop',
				'remoteapplicationtype_id' => $oRemoteApplicationType->GetKey(),
				'url' => 'https://www.combodo.com',
				'auth_user' => 'administrator',
				'auth_pwd' => 'Adm1nistrator++!!',
			]
		);

		[$oLog, $aHeaders] = $this->InvokePrepareHeaders($oRemoteApplication);
		$this->assertEquals(['Content-type: application/json', 'Authorization: Basic YWRtaW5pc3RyYXRvcjpBZG0xbmlzdHJhdG9yKyshIQ=='], $aHeaders);

	}

	public function testPrepareHeaderWithToken()
	{
		$oRemoteApplicationType = $this->GetApplicationType();

		// iTop connexion via a token
		$oRemoteApplicationToken = new RemoteiTopConnectionToken();
		$oRemoteApplicationToken->Set('name', 'Test iTop');
		$oRemoteApplicationToken->Set('remoteapplicationtype_id', $oRemoteApplicationType->GetKey());
		$oRemoteApplicationToken->Set('url', 'https://www.combodo.com');
		$oRemoteApplicationToken->Set('token', 'HAhq2Zfyr24ge!/jqsdf)sCf45A');
		$oRemoteApplicationToken->DBWrite();

		[$oLog, $aHeaders] = $this->InvokePrepareHeaders($oRemoteApplicationToken);
		$this->assertEquals(['Content-type: application/json', 'Auth-Token: HAhq2Zfyr24ge!/jqsdf)sCf45A'], $aHeaders);
	}

	public function testPrepareHeaderWithOauth2()
	{
		$oRemoteApplicationType = $this->GetApplicationType();

		/** @var \Oauth2Client $oOauth2Client */
		$oOauth2Client = $this->createObject(\GitHubOauth2Client::class,
			[
				'name' => 'webhook',
				'client_id' => 'client_123',
				'client_secret' => 'secret456',
				'scope' => 'toto',
				'access_token' => 'access_token1',
				'token_type' => 'Bearer',
				'refresh_token' => 'refresh_token1',
				'access_token_expiration' => '2024-11-13 00:37:48',
			]
		);

		$oRemoteApplication = $this->createObject(\RemoteOauthConnection::class,
			[
				'name' => 'Test iTop',
				'remoteapplicationtype_id' => $oRemoteApplicationType->GetKey(),
				'url' => 'https://www.combodo.com',
				'oauth2client_id' => $oOauth2Client->GetKey(),
			]
		);

		//avoid calling Github IDP
		$oOauth2Service = $this->createMock(Oauth2Service::class);
		Oauth2Service::SetInstance($oOauth2Service);

		Oauth2Service::GetInstance()->InitByOauth2Client($oOauth2Client);
		$sAccessToken = Oauth2Service::GetInstance()->GetAccessToken();

		$oOauth2Service->expects($this->once())
			->method('InitByOauth2Client');

		$oOauth2Service->expects($this->once())
			->method('GetAccessToken')
			->willReturn('GABUZOMEU');

		[$oLog, $aHeaders] = $this->InvokePrepareHeaders($oRemoteApplication);
		$this->assertEquals(['Content-type: application/json', "Authorization: Bearer GABUZOMEU"], $aHeaders);
	}

	/**
	 * @dataProvider TransformHTMLToMSTeamsMarkupProvider
	 */
	public function testTransformHTMLToMSTeamsMarkup(string $stringToTransform, string $type, string $expected): void
	{
		$iRemoteApplicationType = $this->GivenObjectInDB('RemoteApplicationType', [
			'name' => 'My connection type',
		]);

		$iRemoteApplicationConnection = $this->GivenObjectInDB('RemoteApplicationConnection', [
			'name'                     => 'Test localhost',
			'remoteapplicationtype_id' => $iRemoteApplicationType,
			'url'                      => utils::GetAbsoluteUrlAppRoot(),
		]);

		$oActionMicrosoftTeamsNotification = $this->createObject(\ActionMicrosoftTeamsNotification::class, [
			'name'                           => 'Test ActionMicrosoftTeams',
			'remoteapplicationconnection_id' => $iRemoteApplicationConnection,
		]);

		$this->assertEquals($expected, $oActionMicrosoftTeamsNotification->TransformHTMLToMSTeamsMarkup($stringToTransform, $type));
	}

	public function TransformHTMLToMSTeamsMarkupProvider()
	{
		return [
			'Empty string'                                                                 => [
				'stringToTransform'       => '',
				'type'                    => 'adpative_card',
				'expectedFormattedResult' => '',
			],
			'Empty string with type'                                                       => [
				'stringToTransform'       => '',
				'type'                    => 'message_card',
				'expectedFormattedResult' => '',
			],
			'Simple p tag in message card'                                                 => [
				'stringToTransform'       => '<p>Hello !</p>',
				'type'                    => 'message_card',
				'expectedFormattedResult' => 'Hello !',
			],
			'Simple p tag in adaptive card'                                                => [
				'stringToTransform'       => '<p>Hello !</p>',
				'type'                    => 'adaptive_card',
				'expectedFormattedResult' => "Hello !\n\n",
			],
			'Simple a tag in message card'                                                 => [
				'stringToTransform'       => '<a href="https://combodo.com">Combodo</a>',
				'type'                    => 'message_card',
				'expectedFormattedResult' => "[Combodo](https://combodo.com)",
			],
			'Simple a tag in adaptive card'                                                => [
				'stringToTransform'       => '<a href="https://combodo.com">Combodo</a>',
				'type'                    => 'adaptive_card',
				'expectedFormattedResult' => "[Combodo](https://combodo.com)",
			],
			'List of headers in message card'                                              => [
				'stringToTransform'       => '<h1>H1</h1><h2>H2</h2>',
				'type'                    => 'message_card',
				'expectedFormattedResult' => "<h1>H1</h1><h2>H2</h2>",
			],
			'List of headers in adaptive card'                                             => [
				'stringToTransform'       => '<h1>H1</h1><h2>H2</h2>',
				'type'                    => 'adaptive_card',
				'expectedFormattedResult' => "**H1** \n\n**H2** \n\n",
			],
			'Complete message from CKEditor with code, header, links ... in adaptive_card' => [
				'stringToTransform'       => <<<HTML
<h2>
    debut
</h2>
<p>
    <a href="https://combodo.com">Combodo</a>
</p>
<pre><code class="language-plaintext">code normal
var a=1;</code></pre>
<p>
     
</p>
<p>
     
</p>
<p>
     
</p>
<p>
    <code class="language-javascript">code javascript var a=1;</code>
</p>
<pre>    pre tag var a=1;
</pre>
<p>
     
</p>
<p>
    signature
</p>
<p>
    ok
</p>
HTML,
				'type'                    => 'adaptive_card',
				'expectedFormattedResult' => <<<HTML
**
    debut
** 



    [Combodo](https://combodo.com)



code normal
var a=1;

     




     \n



     \n



    code javascript var a=1;\n


    pre tag var a=1;\n

     \n



    signature\n



    ok\n


HTML,

			],
			'Complete message from CKEditor with code, header, links ... in message_card'  => [
				'stringToTransform'       => <<<HTML
<h2>
    debut
</h2>
<p>
    <a href="https://combodo.com">Combodo</a>
</p>
<pre><code class="language-plaintext">code normal
var a=1;</code></pre>
<p>
     
</p>
<p>
     
</p>
<p>
     
</p>
<p>
    <code class="language-javascript">code javascript var a=1;</code>
</p>
<pre>    pre tag var a=1;
</pre>
<p>
     
</p>
<p>
    signature
</p>
<p>
    ok
</p>
HTML,
				'type'                    => 'message_card',
				'expectedFormattedResult' => <<<HTML
<h2>
    debut
</h2>\n
    [Combodo](https://combodo.com)\n
<pre><code class="language-plaintext">code normal
var a=1;</code></pre>\n
     
\n
     
\n
     
\n
    <code class="language-javascript">code javascript var a=1;</code>

<pre>    pre tag var a=1;
</pre>\n
     \n

    signature


    ok\n
HTML,

			],
		];
	}

	public function InvokePrepareHeaders($oRemoteApplication): array {
		$oAction = $this->createObject(ActioniTopWebhook::class,
			[
				'remoteapplicationconnection_id' => $oRemoteApplication->GetKey(),
				'test_remoteapplicationconnection_id' => $oRemoteApplication->GetKey(),
				'name' => 'test',
			]
		);

		$oLog = new EventNotification();

		$aHeaders = $this->InvokeNonPublicMethod(get_class($oAction), 'PrepareHeaders', $oAction, [[], &$oLog]);

		return array($oLog, $aHeaders);
	}

	public function GetApplicationType() : RemoteApplicationType {
		$oRemoteApplicationType = $this->createObject(RemoteApplicationType::class, ['name' => 'iTop']);

		return $oRemoteApplicationType;
	}

	/**
	 * @dataProvider ApplyParamsToJsonProvider
	 */
	public function testApplyParamsToJson($json, array $aContextArgs, $expected, bool $bExpectException)
	{
		if ($bExpectException) {
			$this->expectException(WebhookInvalidJsonValueException::class);
		}

		$oActionWebhook = new ActionWebhook();
		$result = $this->InvokeNonPublicMethod(ActionWebhook::class, 'ApplyParamsToJson', $oActionWebhook,
			[$aContextArgs, $json]);

		if (false === $bExpectException) {
            $this->AssertJSONEquals($expected, $result, $this->getName());
		}
	}

	public function ApplyParamsToJsonProvider(): array
	{
		return [
            'Simple string should be handled correctly' => [
                "payload" => '"toto"',
                "context" => [],
                'expected' => '"toto"',
                'exception' => false
            ],

            'Quotes inside a string should be allowed' => [
                "payload" => '"$this->name$"',
                "context" => ['this->name' => 'to"to'],
                'expected' => '"to\\"to"',
                'exception' => false
            ],

            'New line inside a string should be allowed' => [
                "payload" => '"$this->name$"',
                "context" => ['this->name' => "to\nto"],
                'expected' => '"to\\nto"',
                'exception' => false
            ],

            'Replacement should occur in object array' => [
                "payload" => '{
					"name": "$this->name$"
					}',
                "context" => ['this->name' => 'toto'],
                'expected' => '{
					"name": "toto"
					}',
                'exception' => false
            ],

            'Replacement should occur in object array with multiple depths' => [
                "payload" => '{
					"depth1": {
						"depth2" : {
							"name": "$this->name$"
							}
						}
					}',
                "context" => ['this->name' => 'toto'],
                'expected' => '{
					"depth1": {
						"depth2" : {
							"name": "toto"
							}
						}
					}',
                'exception' => false
            ],

            'Replacement should occur in array' => [
                "payload" => '["$this->name$"]',
                "context" => ['this->name' => 'toto'],
                'expected' => '["toto"]',
                'exception' => false
            ],

            'Replacement should occur in complex structure' => [
                "payload" => '{
			        "depth1": [
					    {
					      "name": "$this->name$"
					    }
					]
				}',
                "context" => ['this->name' => 'toto'],
                'expected' => '{
			        "depth1": [
					    {
					      "name": "toto"
					    }
					]
				}',
                'exception' => false
            ],

            'Pure integer JSON syntax is not supported' => [
                "payload" => '$this->count$',
                "context" => ['this->count' => 2],
                'expected' => '',
                'exception' => true
            ],

            'Emoji should be handled correctly' => [
                "payload" => '"😁"',
                "context" => [],
                'expected' => '"😁"',
                'exception' => false
            ],
        ];
	}

	/**
	 * @dataProvider GetContentTypeProvider
	 */
	public function testGetContentType($sInput, $expectedReturnedContentType)
	{
		$aHeaders = [$sInput];

		$oActionWebhook = new ActionWebhook();
		$result = $this->InvokeNonPublicMethod(ActionWebhook::class, 'GetContentType', $oActionWebhook,
			[$aHeaders]);

		$this->assertSame($expectedReturnedContentType, $result);
	}

	public function GetContentTypeProvider()
	{
		return [
			'null' => [null, null],
			'empty string' => ['', null],

			'typo in header' => ['ContentTypo:application/json', null],

			'correct header JSON' => ['Content-type:application/json', 'application/json'],
			'correct header JSON with spaces and tab' => ['Content-type   :		   application/json', 'application/json'],

			'header no value' => ['Content-type:', null],
			'header value only spaces and tab' => ['Content-type:   	    ', null],
		];
	}

	public static function CallBackWebhook(DBObject $oTriggeringObject, WebResponse $oWebReponse, ActionWebhook $oActionWebhook): void
	{
		call_user_func(static::$oCallBackWebhook, $oTriggeringObject, $oWebReponse, $oActionWebhook);
	}

	public function GivenWebhookActionInvokingCallbackOnResponse(int $iTrigger, Closure $oCallBack): void
	{
		static::$oCallBackWebhook = $oCallBack;
		$iRemoteApplicationType = $this->GivenObjectInDB('RemoteApplicationType', [
			'name' => 'My connection type',
		]);

		$iRemoteApplicationConnection= $this->GivenObjectInDB('RemoteApplicationConnection', [
			'name' => 'Test localhost',
			'remoteapplicationtype_id' => $iRemoteApplicationType,
			'url' => utils::GetAbsoluteUrlAppRoot(),
		]);

		$this->GivenObjectInDB('ActionWebhook', [
			'asynchronous' => 'no',
			'name' => 'Test',
			'status' => 'test',
			'remoteapplicationconnection_id' => $iRemoteApplicationConnection,
			'test_remoteapplicationconnection_id' => $iRemoteApplicationConnection,
			'process_response_callback' => __CLASS__.'::CallBackWebhook',
			'payload' => '{}',
			'trigger_list' => ['trigger_id:' . $iTrigger],
		]);
	}

	public function GivenTriggerOnObjectUpdate(DBObject $object): int
	{
		return $this->GivenObjectInDB(TriggerOnObjectUpdate::class, [
			'description'  => 'My description',
			'target_class' => get_class($object)
		]);
	}

	public function GivenTriggerOnObjectCreate(DBObject $object): int
	{
		return $this->GivenObjectInDB(TriggerOnObjectCreate::class, [
			'description'  => 'My description',
			'target_class' => get_class($object)
		]);
	}

	public function WhenObjectIsUpdatedOnOtherFieldThanName(DBObject $oDBObject)
	{
		$oDBObject->Set('first_name', 'François');
		$oDBObject->DBUpdate();
	}

	public function WhenObjectIsInsertedWithoutNameChange(DBObject $oDBObject)
	{
		$oDBObject->Set('org_id', $this->GetTestOrgId());
		$oDBObject->DBInsert();
	}

    public static function AssertJSONEquals($sExpected, $sActual, $sMessage = '')
    {
        $aExpected = json_decode($sExpected);
        $aActual = json_decode($sActual);
        if(is_null($aExpected)) {
            throw new \Exception('Expected "'.$sExpected.'" is not a valid JSON');
        }
        if(is_null($aActual)) {
            throw new \Exception('Actual "'.$sActual.'" is not a valid JSON');
        }

        static::assertEquals(json_encode($aExpected, JSON_PRETTY_PRINT), json_encode($aActual, JSON_PRETTY_PRINT), $sMessage);
    }
}
