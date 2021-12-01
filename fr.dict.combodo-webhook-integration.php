<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2017 ITOMIG GmbH
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// Menus
Dict::Add('FR FR', 'French', 'Français', array(
	'Menu:Integrations' => 'Intégrations',
	'Dashboard:Integrations:Title' => 'Intégrations avec les applications externes',
	'Dashboard:Integrations:Outgoing:Title' => 'Webhooks d\'intégration sortants',
));

// Base classes
Dict::Add('FR FR', 'French', 'Français', array(
	// RemoteApplicationType
	'Class:RemoteApplicationType' => 'Type d\'application distante',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnection_explanation' => 'Connection explanation~~',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnection_explanation+' => 'Explanation on how to configure a connection for that application, for example where and how to create the endpoint~~',
	'Class:RemoteApplicationType/Attribute:action_explanation' => 'Action explanation~~',
	'Class:RemoteApplicationType/Attribute:action_explanation+' => 'Explanation on how to configure a '.ITOP_APPLICATION_SHORT.' webhook action (activated by a trigger) for that application. For example how to build the payload, or what to expect in the response.~~',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list' => 'Connections~~',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list+' => 'Connections for that application~~',

	// RemoteApplicationConnection
	'Class:RemoteApplicationConnection' => 'Connexion application distante',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id' => 'Application',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id+' => 'Type d\'application de la connexion (mettre \'Générique\' si le votre ne figure pas la liste)',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_remoteapplicationconnection_explanation' => 'Explications',
	'Class:RemoteApplicationConnection/Attribute:environment' => 'Environnement',
	'Class:RemoteApplicationConnection/Attribute:environment+' => 'Type d\'environnement de la connexion',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:1-development' => 'Développement',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:2-test' => 'Test',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:3-production' => 'Production',
	'Class:RemoteApplicationConnection/Attribute:url' => 'URL',
	'Class:RemoteApplicationConnection/Attribute:auth_user' => 'Nom d\'utilisateur',
	'Class:v/Attribute:auth_user+' => 'Nom d\'utilisateur (sur l\'iTop distant) utilisé pour l\'authentification',
	'Class:RemoteApplicationConnection/Attribute:auth_pwd' => 'Mot de passe',
	'Class:RemoteApplicationConnection/Attribute:auth_pwd+' => 'Mot de passe de l\'utilisateur (sur l\'iTop distant) utilisé pour l\'authentification',
	'Class:RemoteApplicationConnection/Attribute:version' => 'Version de l\'API',
	'Class:RemoteApplicationConnection/Attribute:version+' => 'Version de l\'API utilisée sur l\'itop distant (ex : 1.3)',
	'Class:RemoteApplicationConnection/Attribute:actions_list' => 'Appels webhook',
	'Class:RemoteApplicationConnection/Attribute:actions_list+' => 'Appels utilisant cette URL de webhook',
	// - Fieldsets
	'RemoteApplicationConnection:baseinfo' => 'Informations générales',
	'RemoteApplicationConnection:moreinfo' => 'Autres informations',
	'RemoteApplicationConnection:authinfo' => 'Authentification',

	// EventWebhook
	'Class:EventWebhook' => 'Webhook emission event~~',
	'Class:EventWebhook/Attribute:action_finalclass' => 'Final class~~',
	'Class:EventWebhook/Attribute:webhook_url' => 'Webhook URL~~',
	'Class:EventWebhook/Attribute:headers' => 'Entêtes',
	'Class:EventWebhook/Attribute:payload' => 'Payload~~',
	'Class:EventWebhook/Attribute:response' => 'Réponse',

	// ActionWebhook
	'Class:ActionWebhook' => 'Appel de webhook (générique)',
	'Class:ActionWebhook+' => 'Appel webhook pour tout types d\'applications',
	'Class:ActionWebhook/Attribute:language' => 'Langue',
	'Class:ActionWebhook/Attribute:language+' => 'Langue de la notification, principalement utilisée pour filtrer les notifications, mais peut aussi être utilisée pour traduire des libellés d\'attributs',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id' => 'Connexion',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id+' => 'Informations de connexion à utiliser quand le statut est à \'en production\'',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id' => 'Connexion de test',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id+' => 'Informations de connexion à utiliser quand le statut est à \'en test\'',
	'Class:ActionWebhook/Attribute:remoteapplicationtype_action_explanation' => 'Explications',
	'Class:ActionWebhook/Attribute:remoteapplicationtype_action_explanation+' => 'Explanation on how to configure this action for the application of the webhook URL. For example how to build the payload, or what to expect in the response.~~',
	'Class:ActionWebhook/Attribute:method' => 'Méthode',
	'Class:ActionWebhook/Attribute:method+' => 'Méthode HTTP de la requête',
	'Class:ActionWebhook/Attribute:method/Value:get' => 'GET',
	'Class:ActionWebhook/Attribute:method/Value:post' => 'POST',
	'Class:ActionWebhook/Attribute:headers' => 'Entêtes',
	'Class:ActionWebhook/Attribute:headers+' => 'Entêtes de la requête HTTP, seulement une par ligne (ex : \'Content-type: application/json\')',
	'Class:ActionWebhook/Attribute:payload' => 'Payload~~',
	'Class:ActionWebhook/Attribute:payload+' => 'Data sent during the webhook call, most of the time this is a JSON string. Use this if your payload structure is static.

IMPORTANT: Will be ignored if \'Prepare payload callback\' is set~~',
	'Class:ActionWebhook/Attribute:prepare_payload_callback' => 'Prepare payload callback~~',
	'Class:ActionWebhook/Attribute:prepare_payload_callback+' => 'PHP method to prepare payload data to be sent during the webhook call. Use this if your payload structure must dynamically built.

IMPORTANT: If set, the \'Payload\' attribute will be ignored. You can use 2 types of methods:
- From the triggering object itself (eg. UserRequest), must be public. Example: $this->XXX($aContextArgs, $oLog, $oAction)
- From any PHP class, must be static AND public. Name must be name fully qualified. Example: \SomeClass::XXX($oObject, $aContextArgs, $oLog, $oAction)~~',
	'Class:ActionWebhook/Attribute:process_response_callback' => 'Process response callback~~',
	'Class:ActionWebhook/Attribute:process_response_callback+' => 'PHP method to process the webhook call response.

IMPORTANT: You can use 2 types of methods:
- From the triggering object itself (eg. UserRequest), must be public. Example: $this->XXX($oResponse, $oAction)
- From any PHP class, must be static AND public. Name must be name fully qualified. Example: \SomeClass::XXX($oObject, $oResponse, $oAction)~~',
	// - Fieldsets
	'ActionWebhook:baseinfo' => 'Informations générales',
	'ActionWebhook:webhookconnection' => 'Informations de connexion',
	'ActionWebhook:requestsimple' => 'Request, either fill option 1 (simple) ...~~',
	'ActionWebhook:requestadvanced' => '... or option 2 (advanced)~~',
	'ActionWebhook:response' => 'Réponse',
));

// iTop
Dict::Add('FR FR', 'French', 'Français', array(
	// RemoteiTopConnection
	'Class:RemoteiTopConnection' => 'Connexion iTop distant',
	'Class:RemoteiTopConnection/Attribute:auth_user' => 'Nom d\'utilisateur',
	'Class:RemoteiTopConnection/Attribute:auth_user+' => 'Nom d\'utilisateur (sur l\'iTop distant) utilisé pour l\'authentification',
	'Class:RemoteiTopConnection/Attribute:auth_pwd' => 'Mot de passe',
	'Class:RemoteiTopConnection/Attribute:auth_pwd+' => 'Mot de passe de l\'utilisateur (sur l\'iTop distant) utilisé pour l\'authentification',
	'Class:RemoteiTopConnection/Attribute:version' => 'Version de l\'API',
	'Class:RemoteiTopConnection/Attribute:version+' => 'Version de l\'API utilisée sur l\'itop distant (ex : 1.3)',

	// ActioniTopWebhook
	'Class:ActioniTopWebhook' => 'Appel de webhook iTop',
	'Class:ActioniTopWebhook+' => 'Appel de webhook d\'une application iTop distante',
	'Class:ActioniTopWebhook/Attribute:headers+' => 'Entêtes de la requête HTTP, seulement une par ligne (ex : \'Content-type: application/x-www-form-urlencoded\')

IMPORTANT :
- Le \'Content-type\' devrait être \'application/x-www-form-urlencoded\' pour iTop, quand bien même nous envoyons du JSON
- Une entête \'Basic authorization\' sera ajoutée automatiquement à la requête durant l\'envoi, contenant les identifiants de la connexion sélectionnée',
	'Class:ActioniTopWebhook/Attribute:payload' => 'Données JSON',
	'Class:ActioniTopWebhook/Attribute:payload+' => 'The JSON payload, must be a JSON string containing the operation name and parameters, see documentation for detailled information~~',
	// - Fieldsets
	'ActioniTopWebhook:requestparameters' => 'Paramètres de la requêtre',
));

// Slack
Dict::Add('FR FR', 'French', 'Français', array(
	// ActionSlackNotification
	'Class:ActionSlackNotification' => 'Notification Slack',
	'Class:ActionSlackNotification+' => 'Send a notification as a slack message in a channel or to a user~~',
	'Class:ActionSlackNotification/Attribute:message' => 'Message',
	'Class:ActionSlackNotification/Attribute:include_list_attributes' => 'Attributes from the list view~~',
	'Class:ActionSlackNotification/Attribute:include_list_attributes+' => 'Display default attributes from the \'list\' view of the object triggering the notification below the message~~',
	'Class:ActionSlackNotification/Attribute:include_user_info' => 'User info.~~',
	'Class:ActionSlackNotification/Attribute:include_user_info+' => 'Display user information (full name) below the message~~',
	'Class:ActionSlackNotification/Attribute:include_modify_button' => 'Modify button~~',
	'Class:ActionSlackNotification/Attribute:include_modify_button+' => 'Include a button below the message to edit the object in '.ITOP_APPLICATION_SHORT,
	'Class:ActionSlackNotification/Attribute:include_delete_button' => 'Delete button~~',
	'Class:ActionSlackNotification/Attribute:include_delete_button+' => 'Include a button below the message to delete the object in '.ITOP_APPLICATION_SHORT,
	'Class:ActionSlackNotification/Attribute:include_other_actions_button' => 'Other actions buttons~~',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button+' => 'Include other actions (such as transitions available in the current state) below the message~~',
	'Class:ActionSlackNotification/Attribute:specified_other_actions' => 'Other actions~~',
	'Class:ActionSlackNotification/Attribute:specified_other_actions+' => 'Specify which actions to include as buttons below the message. Should be a comma separated list of the actions code (eg. "ev_reopen, ev_close")~~',
	// - Fieldsets
	'ActionSlackNotification:message' => 'Basis message~~',
	'ActionSlackNotification:additionalelements' => 'Additional elements~~',
));

// Rocket.Chat
Dict::Add('EN US', 'English', 'English', array(
	// ActionRocketChatNotification
	'Class:ActionRocketChatNotification' => 'Notification Rocket.Chat',
	'Class:ActionRocketChatNotification+' => 'Send a notification as a rocket chat message in a channel or to a user~~',
	'Class:ActionRocketChatNotification/Attribute:message' => 'Message',
	// - Fieldsets
	'ActionRocketChatNotification:message' => 'Basis message~~',
));
