<?php

namespace Combodo\iTop\Core\Test;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Exception;
use MetaModel;
use RemoteApplicationType;
use RemoteiTopConnection;
use RemoteiTopConnectionToken;
use utils;


/**
 * @group itopRequestMgmt
 * @group restApi
 * @group defaultProfiles
 */
class RestTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;
	const CREATE_TEST_ORG = false;

	static private $sUrl;
	static private $sLogin;
	static private $sPassword = "Iuytrez9876543ç_è-(";

	/**
	 * This method is called before the first test of this test class is run (in the current process).
	 */
	public static function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass();
	}

	/**
	 * This method is called after the last test of this test class is run (in the current process).
	 */
	public static function tearDownAfterClass(): void
	{
		parent::tearDownAfterClass();
	}

	/**
	 * @throws Exception
	 */
	protected function setUp(): void
	{
		parent::setUp();

		static::$sUrl = MetaModel::GetConfig()->Get('app_root_url');
		static::$sLogin = "rest-user-".date('dmYHis');

		$this->CreateTestOrganization();

		$oRestProfile = MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'REST Services User'), true);
		$oAdminProfile = MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'Administrator'), true);

		if (is_object($oRestProfile) && is_object($oAdminProfile)) {
			$oUser = $this->CreateUser(static::$sLogin, $oRestProfile->GetKey(), static::$sPassword);
			$this->AddProfileToUser($oUser, $oAdminProfile->GetKey());
		}
	}

	private function CreateRemoteConnectionToken() : RemoteiTopConnectionToken {
		$oRemoteApplicationType = new RemoteApplicationType();
		$oRemoteApplicationType->Set('name', 'iTop');

		$oRemoteApplicationToken = new RemoteiTopConnectionToken();
		$oRemoteApplicationToken->Set('name', 'Test iTop');
		$oRemoteApplicationToken->Set('remoteapplicationtype_id', $oRemoteApplicationType->GetKey());
		$oRemoteApplicationToken->Set('url', 'https://www.combodo.com');
		$oRemoteApplicationToken->Set('token', 'HAhq2Zfyr24ge!/jqsdf)sCf45A');
		return $oRemoteApplicationToken;
	}

	public function CoreApiGetProvider() {
		return [
			'query OK' => [ <<<JSON
{
    "operation": "core/get",
    "class": "RemoteiTopConnectionToken",
    "key": "SELECT RemoteiTopConnectionToken",
    "output_fields": "name, token",
    "limit": "3",
    "page": "1"
}
JSON ],
			/*'query FAIL' => [ <<<JSON
{
    "operation": "core/get",
    "class": "RemoteiTopConnectionToken",
    "key": "SELECT RemoteiTopConnectionToken",
    "output_fields": "name,actions_list",
    "limit": "3",
    "page": "1"
}
JSON ],*/
		];
	}

	/**
	 * @dataProvider CoreApiGetProvider
	 */
	public function testCoreApiGet($sJsonGetQuery){
		// Create ticket
		$description = date('dmY H:i:s');
		$oRCT = $this->CreateRemoteConnectionToken($description);
		$iId = $oRCT->GetKey();

		$sJSONOutput = $this->CallCoreRestApi_Internally($sJsonGetQuery);

		$aResponse = json_decode($sJSONOutput, true);
		$sCode = $aResponse['code'] ?? -1;
		$this->assertEquals("0", $sCode, "code error ($sCode) with API query\n $sJsonGetQuery \n received JSON Response:\n$sJSONOutput");
		$sMsg = $aResponse['message'] ?? "no message";
		$this->assertTrue(false !== strpos($sMsg, 'Found'), $sJSONOutput, "code message ($sMsg) with API query\n $sJsonGetQuery \n received JSON Response:\n$sJSONOutput");
	}

	/**
	 * @param string|null $sJsonDataContent If null, then no data is posted and the service should reply with an error
	 * @param string|null $sCallbackName JSONP callback
	 * @param bool $bJSONDataAsFile Set to true to test with the data provided to curl as a file
	 *
	 * @return bool|string
	 */
	private function CallRestApi_HTTP(string $sJsonDataContent = null, string $sCallbackName = null, bool $bJSONDataAsFile = false)
	{
		$ch = curl_init();
		$aPostFields = [
			'version' => '1.3',
			'auth_user' => static::$sLogin,
			'auth_pwd' => static::$sPassword,
		];

		$sTmpFile = null;
		if (!is_null($sJsonDataContent)) {
			if ($bJSONDataAsFile) {
				$sTmpFile = tempnam(sys_get_temp_dir(), 'jsondata_');
				file_put_contents($sTmpFile, $sJsonDataContent);

				$oCurlFile = curl_file_create($sTmpFile);
				$aPostFields['json_data'] = $oCurlFile;
			}
			else {
				$aPostFields['json_data'] = $sJsonDataContent;
			}
		}

		if (utils::IsNotNullOrEmptyString($sCallbackName)) {
			$aPostFields['callback'] = $sCallbackName;
		}

		curl_setopt($ch, CURLOPT_URL, static::$sUrl."/webservices/rest.php");
		curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
		curl_setopt($ch, CURLOPT_POSTFIELDS, $aPostFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Force disable of certificate check as most of dev / test env have a self-signed certificate
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		$sJson = curl_exec($ch);
		curl_close ($ch);

		if (!is_null($sTmpFile)) {
			unlink($sTmpFile);
		}

		return $sJson;
	}

	private function CallCoreRestApi_Internally(string $sJsonDataContent)
	{
		$oJsonData = json_decode($sJsonDataContent);
		$sOperation = $oJsonData->operation;

		//\UserRights::Login(static::$sLogin);
		\CMDBObject::SetTrackOrigin('webservice-rest');
		\CMDBObject::SetTrackInfo('test');

		$oRestSP = new \CoreServices();
		$oResult = $oRestSP->ExecOperation('1.3', $sOperation, $oJsonData);

		//\UserRights::Logoff();

		return json_encode($oResult);
	}
}
