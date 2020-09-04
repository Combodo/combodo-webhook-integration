<?php

namespace Combodo\iTop\Core\Notification\Action;

use ActionNotification;
use ApplicationContext;
use Combodo\iTop\Service\WebRequestSender;
use EventWebhook;
use Exception;
use MetaModel;
use UserRights;

abstract class _ActionWebhook extends ActionNotification
{
	/** @var array $aRequestErrors Errors occurring during the execution */
	protected $aRequestErrors;

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
			$oRequest = $this->PrepareWebRequest($aContextArgs, $oLog);

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
					// TODO: Refactor this
					$sResponseCallback = $this->Get('process_response_callback');
					// Check if callback is in the action class itself
					if(stripos($sResponseCallback, '$this->') !== false)
					{
						$sMethodName = str_ireplace('$this->', '', $sResponseCallback);
						$this->$sMethodName($aContextArgs['this->object()'], $aResult['response']);
					}
					// Otherwise check if callback is callable as a static method
					elseif(is_callable($sResponseCallback))
					{
						call_user_func($sResponseCallback, $aContextArgs['this->object()'], $aResult['response']);
					}
					// Otherwise there is a problem, we cannot call the callback
					elseif(empty($sResponseCallback) === false)
					{
						throw new Exception('Process response callback is not callable ('.$sResponseCallback.')');
					}
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
	 * Prepare and return the WebRequest to be sent
	 *
	 * @param array              $aContextArgs
	 * @param \EventNotification $oLog
	 *
	 * @return \Combodo\iTop\Core\WebRequest
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	abstract protected function PrepareWebRequest($aContextArgs, &$oLog);
}
