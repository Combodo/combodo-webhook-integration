<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2017 ITOMIG GmbH
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// Menus
Dict::Add('EN US', 'English', 'English', array(
	'Menu:Integrations' => 'Integrations',
	'Dashboard:Integrations:Title' => 'Integrations with external applications',
	'Dashboard:Integrations:Outgoing:Title' => 'Outgoing webhook integrations',
	'Dashboard:Integrations:ActionWebhookList:Title' => 'Webhook type actions',
));

// Base classes
Dict::Add('EN US', 'English', 'English', array(
	// RemoteApplicationType
	'Class:RemoteApplicationType' => 'Remote application type',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnection_explanation' => 'Connection explanation',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnection_explanation+' => 'Explanation on how to configure a connection for that application, for example where and how to create the endpoint',
	'Class:RemoteApplicationType/Attribute:action_explanation' => 'Action explanation',
	'Class:RemoteApplicationType/Attribute:action_explanation+' => 'Explanation on how to configure a '.ITOP_APPLICATION_SHORT.' webhook action (activated by a trigger) for that application. For example how to build the payload, or what to expect in the response.',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list' => 'Connections',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list+' => 'Connections for that application',

	// RemoteApplicationConnection
	'Class:RemoteApplicationConnection' => 'Remote application connection',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id' => 'Application type',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id+' => 'Type of application the connection is for (use \'Generic\' if yours is not in the list)',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_remoteapplicationconnection_explanation' => 'Explanation',
	'Class:RemoteApplicationConnection/Attribute:environment' => 'Environment',
	'Class:RemoteApplicationConnection/Attribute:environment+' => 'Type of environment of the connection',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:1-development' => 'Development',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:2-test' => 'Test',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:3-production' => 'Production',
	'Class:RemoteApplicationConnection/Attribute:url' => 'URL',
	'Class:RemoteApplicationConnection/Attribute:actions_list' => 'Webhook notifications',
	'Class:RemoteApplicationConnection/Attribute:actions_list+' => 'Webhook notifications using this connection',
	// - Fieldsets
	'RemoteApplicationConnection:baseinfo' => 'General information',
	'RemoteApplicationConnection:moreinfo' => 'More information',
	'RemoteApplicationConnection:authinfo' => 'Authentication',

	// EventWebhook
	'Class:EventWebhook' => 'Webhook emission event',
	'Class:EventWebhook/Attribute:action_finalclass' => 'Final class',
	'Class:EventWebhook/Attribute:webhook_url' => 'Webhook URL',
	'Class:EventWebhook/Attribute:headers' => 'Headers',
	'Class:EventWebhook/Attribute:payload' => 'Payload',
	'Class:EventWebhook/Attribute:response' => 'Response',

	// ActionWebhook
	'Class:ActionWebhook' => 'Webhook call (generic)',
	'Class:ActionWebhook+' => 'Webhook call for any kind of application',
	'Class:ActionWebhook/Attribute:language' => 'Language',
	'Class:ActionWebhook/Attribute:language+' => 'Language of this notification, mostly used when searching for notifications but might also be used to translate attributes label',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id' => 'Connection',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id+' => 'Connection information to use when status is \'in production\'',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id' => 'Test connection',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id+' => 'Connection information to use when status is \'being tested\'',
	'Class:ActionWebhook/Attribute:remoteapplicationtype_action_explanation' => 'Explanation',
	'Class:ActionWebhook/Attribute:remoteapplicationtype_action_explanation+' => 'Explanation on how to configure this action for the connection\'s application. For example how to build the payload, or what to expect in the response.',
	'Class:ActionWebhook/Attribute:method' => 'Method',
	'Class:ActionWebhook/Attribute:method+' => 'Method of the HTTP request',
	'Class:ActionWebhook/Attribute:method/Value:get' => 'GET',
	'Class:ActionWebhook/Attribute:method/Value:post' => 'POST',
	'Class:ActionWebhook/Attribute:method/Value:put' => 'PUT',
	'Class:ActionWebhook/Attribute:method/Value:patch' => 'PATCH',
	'Class:ActionWebhook/Attribute:method/Value:delete' => 'DELETE',
	'Class:ActionWebhook/Attribute:headers' => 'Headers',
	'Class:ActionWebhook/Attribute:headers+' => 'Headers of the HTTP request, must be one per line (eg. \'Content-type: application/json\')',
	'Class:ActionWebhook/Attribute:payload' => 'Payload',
	'Class:ActionWebhook/Attribute:payload+' => 'Data sent during the webhook call, most of the time this is a JSON string. Use this if your payload structure is static.

IMPORTANT: Will be ignored if \'Prepare payload callback\' is set',
	'Class:ActionWebhook/Attribute:prepare_payload_callback' => 'Prepare payload callback',
	'Class:ActionWebhook/Attribute:prepare_payload_callback+' => 'PHP method to prepare payload data to be sent during the webhook call. Use this if your payload structure must be dynamically built.

You can use 2 types of methods:
- From the triggering object itself (eg. UserRequest), must be public. Example: $this->XXX($aContextArgs, $oLog, $oAction)
- From any PHP class, must be static AND public. Name must be name fully qualified. Example: \SomeClass::XXX($oObject, $aContextArgs, $oLog, $oAction)

IMPORTANT: If set, the \'Payload\' attribute will be ignored.',
	'Class:ActionWebhook/Attribute:process_response_callback' => 'Process response callback',
	'Class:ActionWebhook/Attribute:process_response_callback+' => 'PHP method to process the webhook call response.

You can use 2 types of methods:
- From the triggering object itself (eg. UserRequest), must be public. Example: $this->XXX($oResponse, $oAction)
- From any PHP class, must be static AND public. Name must be name fully qualified. Example: \SomeClass::XXX($oObject, $oResponse, $oAction)
- $oResponse can be null in some cases (eg. request failed to send)',
	// - Fieldsets
	'ActionWebhook:baseinfo' => 'General information',
	'ActionWebhook:moreinfo' => 'More information',
	'ActionWebhook:webhookconnection' => 'Webhook connection',
	// Note: This one is used by derivated classes
	'ActionWebhook:requestparameters' => 'Request parameters',
	'ActionWebhook:advancedparameters' => 'Advanced parameters',
));

// iTop
Dict::Add('EN US', 'English', 'English', array(
	// RemoteiTopConnection
	'Class:RemoteiTopConnection' => 'Remote iTop connection',
	'Class:RemoteiTopConnection/Attribute:auth_user' => 'Auth. user',
	'Class:RemoteiTopConnection/Attribute:auth_user+' => 'Login of the user (on the remote iTop) used for the authentication',
	'Class:RemoteiTopConnection/Attribute:auth_pwd' => 'Auth. password',
	'Class:RemoteiTopConnection/Attribute:auth_pwd+' => 'Password of the user (on the remote iTop) used for the authentication',
	'Class:RemoteiTopConnection/Attribute:version' => 'API version',
	'Class:RemoteiTopConnection/Attribute:version+' => 'Version of the API called (eg. 1.3)',

	// ActioniTopWebhook
	'Class:ActioniTopWebhook' => 'iTop webhook call',
	'Class:ActioniTopWebhook+' => 'Webhook call to a remote iTop application',
	'Class:ActioniTopWebhook/Attribute:headers+' => 'Headers of the HTTP request, must be one per line (eg. \'Content-type: application/x-www-form-urlencoded\')

IMPORTANT:
- \'Content-type\' should be set to \'application/x-www-form-urlencoded\' for iTop, even though we send JSON
- A \'Basic authorization\' header will be append automatically to request during sending, containing the credentials from the selected connection',
	'Class:ActioniTopWebhook/Attribute:payload' => 'JSON data',
	'Class:ActioniTopWebhook/Attribute:payload+' => 'The JSON payload, must be a JSON string containing the operation name and parameters, see documentation for detailled information',
	'Class:ActioniTopWebhook/Attribute:prepare_payload_callback+' => 'PHP method to prepare payload data to be sent during the webhook call. Use this if your payload structure must be dynamically built.

You can use 2 types of methods:
- From the triggering object itself (eg. UserRequest), must be public. Example: $this->XXX($aContextArgs, $oLog, $oAction)
- From any PHP class, must be static AND public. Name must be name fully qualified. Example: \SomeClass::XXX($oObject, $aContextArgs, $oLog, $oAction)

IMPORTANT: If set, the \'JSON data\' attribute will be ignored.',
));

// Slack
Dict::Add('EN US', 'English', 'English', array(
	// ActionSlackNotification
	'Class:ActionSlackNotification' => 'Slack notification',
	'Class:ActionSlackNotification+' => 'Send a notification as a slack message in a channel or to a user',
	'Class:ActionSlackNotification/Attribute:message' => 'Message',
	'Class:ActionSlackNotification/Attribute:include_list_attributes' => 'Attributes from',
	'Class:ActionSlackNotification/Attribute:include_list_attributes+' => 'Display additional attributes below the message. They can be either from the usual \'list\' view or the custom \'slack\' view of the object triggering the notification . Note that the \'slack\' view must be defined in the datamodel first (zlist)',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:list' => 'the usual list view',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:slack' => 'the custom slack view',
	'Class:ActionSlackNotification/Attribute:include_user_info' => 'User info.',
	'Class:ActionSlackNotification/Attribute:include_user_info+' => 'Display user information (full name) below the message',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:no' => 'No',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:yes' => 'Yes',
	'Class:ActionSlackNotification/Attribute:include_modify_button' => 'Modify button',
	'Class:ActionSlackNotification/Attribute:include_modify_button+' => 'Include a button below the message to edit the object in '.ITOP_APPLICATION_SHORT,
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:no' => 'No',
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:yes' => 'Yes',
	'Class:ActionSlackNotification/Attribute:include_delete_button' => 'Delete button',
	'Class:ActionSlackNotification/Attribute:include_delete_button+' => 'Include a button below the message to delete the object in '.ITOP_APPLICATION_SHORT,
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:no' => 'No',
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:yes' => 'Yes',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button' => 'Other actions buttons',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button+' => 'Include other actions (such as transitions available in the current state) below the message',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:no' => 'No',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:specify' => 'Specify',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:yes' => 'Yes',
	'Class:ActionSlackNotification/Attribute:specified_other_actions' => 'Other actions codes',
	'Class:ActionSlackNotification/Attribute:specified_other_actions+' => 'Specify which actions to include as buttons below the message. Should be a comma separated list of the actions codes (eg. \'ev_reopen, ev_close\')',
	'Class:ActionSlackNotification/Attribute:prepare_payload_callback+' => 'PHP method to prepare payload data to be sent during the webhook call. Use this if the standard options are not flexible enough or if your payload structure must be dynamically built.

You can use 2 types of methods:
- From the triggering object itself (eg. UserRequest), must be public. Example: $this->XXX($aContextArgs, $oLog, $oAction)
- From any PHP class, must be static AND public. Name must be name fully qualified. Example: \SomeClass::XXX($oObject, $aContextArgs, $oLog, $oAction)

IMPORTANT: If set, the \'message\' and all \'additional elements\' will be ignored.',
	// - Fieldsets
	'ActionSlackNotification:message' => 'Basis message',
	'ActionSlackNotification:additionalelements' => 'Additional elements to include',

	// Payload
	'ActionSlackNotification:Payload:BlockKit:UserInfo' => 'Notification from <%2$s|%1$s> (%3$s)',
));

// Rocket.Chat
Dict::Add('EN US', 'English', 'English', array(
	// ActionRocketChatNotification
	'Class:ActionRocketChatNotification' => 'Rocket.Chat notification',
	'Class:ActionRocketChatNotification+' => 'Send a notification as a rocket chat message in a channel or to a user',
	'Class:ActionRocketChatNotification/Attribute:message' => 'Message',
	'Class:ActionRocketChatNotification/Attribute:message+' => 'Message that will be displayed in the chat',
	'Class:ActionRocketChatNotification/Attribute:bot_alias' => 'Alias',
	'Class:ActionRocketChatNotification/Attribute:bot_alias+' => 'Overrides the default bot alias, will appear before the username of the message',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar' => 'Image avatar',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar+' => 'Overrides the default bot avatar, must be an absolute URL to the image to use',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar' => 'Emoji avatar',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar+' => 'Overrides the default bot avatar, can be any Rocket.Chat emojis (eg. :ghost:, :white_check_mark:, ...). Note that if an URL avatar is set, the emoji won\'t be displayed.',
	'Class:ActionRocketChatNotification/Attribute:prepare_payload_callback+' => 'PHP method to prepare payload data to be sent during the webhook call. Use this if the standard options are not flexible enough or if your payload structure must be dynamically built.

You can use 2 types of methods:
- From the triggering object itself (eg. UserRequest), must be public. Example: $this->XXX($aContextArgs, $oLog, $oAction)
- From any PHP class, must be static AND public. Name must be name fully qualified. Example: \SomeClass::XXX($oObject, $aContextArgs, $oLog, $oAction)

IMPORTANT: If set, the \'message\' and all \'bot information\' will be ignored.',
	// - Fieldsets
	'ActionRocketChatNotification:message' => 'Basis message',
	'ActionRocketChatNotification:additionalelements' => 'Bot information'
));
