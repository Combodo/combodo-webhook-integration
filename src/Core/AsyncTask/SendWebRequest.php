<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Core\AsyncTask;


use AsyncTask;
use AttributeString;
use AttributeLongText;
use Combodo\iTop\Core\WebRequest;
use Combodo\iTop\Service\WebRequestSender;
use MetaModel;

class SendWebRequest extends AsyncTask
{
	/**
	 * @inheritDoc
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "autoincrement",
			"name_attcode" => "created",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_async_send_web_request",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeLongText("request", array("allowed_values"=>null, "sql"=>"request", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
	}

	/**
	 * Add the $oWebRequest to the queue to be send later (background task for example)
	 *
	 * @param \Combodo\iTop\Core\WebRequest $oWebRequest
	 * @param \EventNotification|null       $oLog
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 *
	 * @return void
	 */
	public static function AddToQueue(WebRequest $oWebRequest, $sResponseCallback, $oLog = null)
	{
		$oNew = new static();
		if ($oLog)
		{
			$oNew->Set('event_id', $oLog->GetKey());
		}

		$oNew->Set('request', serialize($oWebRequest));
		$oNew->DBInsert();
	}

	/**
	 * @inheritDoc
	 */
	public function DoProcess()
	{
		$aIssues = array();
		$oWebRequest = unserialize($this->Get('request'));

		// Retrieve log event
		/** @var \AttributeExternalKey $oAttDef */
		$oAttDef = MetaModel::AttributeDef(get_class(), 'event_id');
		$oLog = MetaModel::GetObject($oAttDef->GetTargetClass(), $this->Get('event_id'), false, true);

		$oSenderService = WebRequestSender::GetInstance();
		$aResult = $oSenderService->Send($oWebRequest, $aIssues, $oLog, WebRequestSender::ENUM_SEND_MODE_SYNC);
		switch ($aResult['sender_status'])
		{
			case WebRequestSender::ENUM_SEND_STATE_OK:
				return 'Sent';

			case WebRequestSender::ENUM_SEND_STATE_PENDING:
				return 'Whoops! Seems like a bug occurred, the request should be sent in synchronous mode';

			case WebRequestSender::ENUM_SEND_STATE_ERROR:
				return 'Failed: '.implode(', ', $aIssues);
		}
	}
}