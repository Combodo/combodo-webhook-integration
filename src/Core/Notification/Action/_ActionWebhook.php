<?php

namespace Combodo\iTop\Core\Notification\Action;

use ActionNotification;
use ApplicationContext;
use Combodo\iTop\Core\WebResponse;
use Combodo\iTop\Service\WebRequestSender;
use EventWebhook;
use Exception;
use IssueLog;
use MetaModel;
use UserRights;

abstract class _ActionWebhook extends ActionNotification
{
	/** @var array $aRequestErrors Errors occurring during the execution */
	protected $aRequestErrors;

	/**
	 * Default WebResponse handler for a WebRequest made by an _ActionWebhook
	 *
	 * @param \Combodo\iTop\Core\WebResponse $oResponse
	 * @param array                          $aParams Parameters of the handler, must contain at least the triggering object and activated webhook action information:
	 *                                                [
	 *                                                  'oTriggeringObject' => ['class' => <CLASS>, 'id' => <ID>],
	 *                                                  'oActionWebhook' => ['class' => <CLASS>, 'id' => <ID>],
	 *                                                ]
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public static function ExecuteResponseHandler(WebResponse $oResponse, array $aParams)
	{
		// Retrieve objects from params
		if (! array_key_exists('oTriggeringObject', $aParams) || ! array_key_exists('oActionWebhook', $aParams)) {
			IssueLog::Error('Missing parameters in response handler. Expecting at least oTriggeringObject and oActionWebhook', 'console', [
				'oResponse' => $oResponse,
				'aParams' => $aParams,
			]);
			throw new Exception('Missing parameters in response handler. See error log for details.');
		}

		$oTriggeringObject = MetaModel::GetObject($aParams['oTriggeringObject']['class'], $aParams['oTriggeringObject']['id'], true, true);
		$oActionWebhook = MetaModel::GetObject($aParams['oActionWebhook']['class'], $aParams['oActionWebhook']['id'], true, true);

		// Check if callback is on the object itself
		$sResponseCallback = $oActionWebhook->Get('process_response_callback');
		if(stripos($sResponseCallback, '$this->') !== false)
		{
			$sMethodName = str_ireplace('$this->', '', $sResponseCallback);
			$oTriggeringObject->$sMethodName($oResponse, $oActionWebhook);
		}
		// Otherwise, check if callback is callable as a static method
		elseif(is_callable($sResponseCallback))
		{
			call_user_func($sResponseCallback, $oTriggeringObject, $oResponse, $oActionWebhook);
		}
		// Otherwise, there is a problem, we cannot call the callback
		elseif(empty($sResponseCallback) === false)
		{
			throw new Exception('Process response callback is not callable ('.$sResponseCallback.')');
		}
	}

	/**
	 * @inheritDoc
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function DoExecute($oTrigger, $aContextArgs)
	{
		// Create notification object to log status information if enabled
		if (MetaModel::IsLogEnabledNotification())
		{
			$oLog = new EventWebhook();
			if ($this->IsBeingTested())
			{
				$oLog->Set('message', 'TEST - Notification pending');
			}
			else
			{
				$oLog->Set('message', 'Notification pending');
			}
			$oLog->Set('userinfo', UserRights::GetUser());
			$oLog->Set('action_finalclass', $this->Get('finalclass'));
			$oLog->Set('trigger_id', $oTrigger->GetKey());
			$oLog->Set('action_id', $this->GetKey());
			$oLog->Set('object_id', $aContextArgs ['this->object()']->GetKey());
			$oLog->DBInsertNoReload();
		}
		else
		{
			$oLog = null;
		}

		try
		{
			// Execute Request
			$sRes = $this->_DoExecute($oTrigger, $aContextArgs, $oLog);

			// Logging Feedback
			if ($oLog)
			{
				$sPrefix = ($this->IsBeingTested()) ? 'TEST - ' : '';
				$oLog->Set('message', $sPrefix.$sRes);
			}

		}
		catch (Exception $oException)
		{
			if ($oLog)
			{
				$oLog->Set('message', 'Error: '.$oException->getMessage());
			}
		}
		if ($oLog)
		{
			$oLog->DBUpdate();
		}
	}

	/**
	 * Do the execution itself
	 *
	 * @param \Trigger           $oTrigger TriggerObject which called the action
	 * @param array              $aContextArgs
	 * @param \EventNotification $oLog     Reference to the Log Object for store information in EventNotification
	 *
	 * @return string
	 * @throws \Exception
	 */
	protected function _DoExecute($oTrigger, $aContextArgs, &$oLog)
	{
		$sPreviousUrlMaker = ApplicationContext::SetUrlMakerClass();
		try
		{
			$this->aRequestErrors = array();

			$sActionClass = get_called_class();
			/** @var \DBObject $oTriggeringObject */
			$oTriggeringObject = $aContextArgs['this->object()'];

			$oRequest = $this->PrepareWebRequest($aContextArgs, $oLog);

			// Set default response handler if not already defined (this also custom ActionWebhook classes to define their own)
			if ($oRequest->HasResponseHandler() === false) {
				$oRequest->SetResponseHandlerName($sActionClass.'::ExecuteResponseHandler')
				->SetResponseHandlerParams([
					'oTriggeringObject' => [
						'class' => get_class($oTriggeringObject),
						'id' => $oTriggeringObject->GetKey(),
					],
					'oActionWebhook' => [
						'class' => $sActionClass,
						'id' => $this->GetKey(),
					],
				]);
			}

			// Errors during preparation
			if (!empty($this->m_aWebrequestErrors))
			{
				return 'Errors: '.implode(', ', $this->m_aWebrequestErrors);
			}

			if ($this->IsBeingTested() && empty($oRequest->GetURL()))
			{
				return 'Not sent as there was no test webhook URL defined';
			}

			$oSender = WebRequestSender::GetInstance();
			$aResult = $oSender->Send($oRequest, $this->aRequestErrors, $oLog);

			switch ($aResult['sender_status'])
			{
				case WebRequestSender::ENUM_SEND_STATE_OK:
					return 'Sent';

				case WebRequestSender::ENUM_SEND_STATE_PENDING:
					return 'Pending';

				case WebRequestSender::ENUM_SEND_STATE_ERROR;
					return 'Errors: '.implode(', ', $this->aRequestErrors);
			}
		}
		catch (Exception $oException)
		{
			ApplicationContext::SetUrlMakerClass($sPreviousUrlMaker);
			throw $oException;
		}
		ApplicationContext::SetUrlMakerClass($sPreviousUrlMaker);

		return 'Bug: Unknown behavior, check the event notification log.';
	}

	/**
	 * @param array              $aContextArgs
	 * @param \EventNotification $oLog
	 *
	 * @return \Combodo\iTop\Core\WebRequest Prepare and return the WebRequest to be sent
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	abstract protected function PrepareWebRequest(array $aContextArgs, \EventNotification &$oLog);
}
