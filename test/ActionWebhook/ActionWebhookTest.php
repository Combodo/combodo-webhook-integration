<?php
/*
 * Copyright (C) 2023 Combodo SARL
 * This file is part of iTop.
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Core\Test;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use RemoteiTopConnection;
use RemoteiTopConnectionToken;
use RemoteApplicationType;
use ActioniTopWebhook;
use EventNotification;
use MetaModel;


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ActionWebhookTest extends ItopDataTestCase
{
	protected function setUp(): void {
		parent::setUp();
	}

	public function testPrepareHeader()
	{
		$oRemoteApplicationType = new RemoteApplicationType();
		$oRemoteApplicationType->Set('name', 'iTop');
		$oRemoteApplicationType->DBWrite();

		// iTop connexion via username / password
		$oRemoteApplication = new RemoteiTopConnection();
		$oRemoteApplication->Set('name', 'Test iTop');
		$oRemoteApplication->Set('remoteapplicationtype_id', $oRemoteApplicationType->GetKey());
		$oRemoteApplication->Set('url', 'https://www.combodo.com');
		$oRemoteApplication->Set('auth_user', 'administrator');
		$oRemoteApplication->Set('auth_pwd', 'Adm1nistrator++!!');
		$oRemoteApplication->DBWrite();
		
		$oAction = new ActioniTopWebhook();
		$oAction->Set('remoteapplicationconnection_id', $oRemoteApplication->GetKey());
		$oAction->Set('test_remoteapplicationconnection_id', $oRemoteApplication->GetKey());
		$oAction->Set('name', 'test');
		$oAction->DBWrite();
		
		$oLog = new EventNotification();
		
		$aHeaders = $this->InvokeNonPublicMethod(get_class($oAction), 'PrepareHeaders', $oAction, [[], &$oLog]);
		$this->assertEquals(['Content-type: application/json', 'Authorization: Basic YWRtaW5pc3RyYXRvcjpBZG0xbmlzdHJhdG9yKyshIQ=='], $aHeaders);

		// iTop connexion via a token
		$oRemoteApplicationToken = new RemoteiTopConnectionToken();
		$oRemoteApplicationToken->Set('name', 'Test iTop');
		$oRemoteApplicationToken->Set('remoteapplicationtype_id', $oRemoteApplicationType->GetKey());
		$oRemoteApplicationToken->Set('url', 'https://www.combodo.com');
		$oRemoteApplicationToken->Set('token', 'HAhq2Zfyr24ge!/jqsdf)sCf45A');
		$oRemoteApplicationToken->DBWrite();
		
		$oAction2 = new ActioniTopWebhook();
		$oAction2->Set('remoteapplicationconnection_id', $oRemoteApplicationToken->GetKey());
		$oAction2->Set('test_remoteapplicationconnection_id', $oRemoteApplicationToken->GetKey());
		$oAction2->Set('name', 'test2');
		$oAction2->DBWrite();
		
		$oLog = new EventNotification();
		
		$aHeaders = $this->InvokeNonPublicMethod(get_class($oAction), 'PrepareHeaders', $oAction2, [[], &$oLog]);
		$this->assertEquals(['Content-type: application/json', 'Auth-Token: HAhq2Zfyr24ge!/jqsdf)sCf45A'], $aHeaders);
		
	}
}
