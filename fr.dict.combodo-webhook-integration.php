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
	'Dashboard:Integrations:ActionWebhookList:Title' => 'Actions de type webhook',
));

// Base classes
Dict::Add('FR FR', 'French', 'Français', array(
	// RemoteApplicationType
	'Class:RemoteApplicationType' => 'Type d\'application distante',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list' => 'Connexions',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list+' => 'Connexions pour cette application',

	// RemoteApplicationConnection
	'Class:RemoteApplicationConnection' => 'Connexion application distante',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id' => 'Application',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id+' => 'Type d\'application de la connexion (mettre \'Générique\' si le votre ne figure pas la liste)',
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
	'Class:EventWebhook' => 'Evènement d\'envoi de webhook',
	'Class:EventWebhook/Attribute:action_finalclass' => 'Classe finale',
	'Class:EventWebhook/Attribute:webhook_url' => 'URL du webhook',
	'Class:EventWebhook/Attribute:headers' => 'Entêtes',
	'Class:EventWebhook/Attribute:payload' => 'Charge utile',
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
	'Class:ActionWebhook/Attribute:method' => 'Méthode',
	'Class:ActionWebhook/Attribute:method+' => 'Méthode HTTP de la requête',
	'Class:ActionWebhook/Attribute:method/Value:get' => 'GET',
	'Class:ActionWebhook/Attribute:method/Value:post' => 'POST',
	'Class:ActionWebhook/Attribute:method/Value:put' => 'PUT',
	'Class:ActionWebhook/Attribute:method/Value:patch' => 'PATCH',
	'Class:ActionWebhook/Attribute:method/Value:delete' => 'DELETE',
	'Class:ActionWebhook/Attribute:method/Value:head' => 'HEAD',
	'Class:ActionWebhook/Attribute:headers' => 'Entêtes',
	'Class:ActionWebhook/Attribute:headers+' => 'Entêtes de la requête HTTP, seulement une par ligne (ex : \'Content-type: application/json\')',
	'Class:ActionWebhook/Attribute:payload' => 'Charge utile',
	'Class:ActionWebhook/Attribute:payload+' => 'Données envoyées lors de l\'appel webhook, une chaîne JSON le plus souvent. Utiliser ce champ si la structure de la charge utile est statique / fixe.

IMPORTANT : Sera ignoré si le champ \'Callback de préparation de la charge utile\' est renseigné',
	'Class:ActionWebhook/Attribute:prepare_payload_callback' => 'Callback de préparation de la charge utile',
	'Class:ActionWebhook/Attribute:prepare_payload_callback+' => 'Méthode PHP pour préparer les données de la charge utile à envoyer lors de l\'appel du webhook. Utiliser ce champ si la charge utile a une structure qui doit être construite dynamiquement.

IMPORTANT : Si renseigné, le champ \'Charge utile\' sera ignoré. Vous pouvez utiliser 2 types de méthodes :
- Méthode de l\'objet déclenchant l\'action (ex : Demande utilisateur), doit être publique. Exemple : $this->XXX($aContextArgs, $oLog, $oAction)
- Méthode de n\'importe quelle classe PHP, doit être statique ET publique. Le nom doit être entièrement qualifié (inclure le namespace). Exemple: \UneClasse::XXX($oObject, $aContextArgs, $oLog, $oAction)',
	'Class:ActionWebhook/Attribute:process_response_callback' => 'Callback de traitement de la réponse',
	'Class:ActionWebhook/Attribute:process_response_callback+' => 'Méthode PHP pour traiter la réponse de reçue lors de l\'appel webhook.

IMPORTANT : Vous pouvez utiliser 2 types de méthodes :
- Méthode de l\'objet déclenchant l\'action (ex : Demande utilisateur), doit être publique. Example : $this->XXX($oResponse, $oAction)
- Méthode de n\'importe quelle classe PHP, doit être statique ET publique. Le nom doit être entièrement qualifié (inclure le namespace). Exemple : \UneClass::XXX($oObject, $oResponse, $oAction)
- $oResponse peut être null dans certains cas (ex : échec de l\'envoi de la requête)',
	'Class:ActionWebhook:Error:payload:InvalidJson' => 'Attention : Le champ Charge Utile contient un JSON invalide (les placeholder n\'ont pas été remplacés pour ce contrôle). L\'action ne pourra pas être exécutée avec un JSON invalide.',
	// - Fieldsets
	'ActionWebhook:baseinfo' => 'Informations générales',
	'ActionWebhook:webhookconnection' => 'Informations de connexion',
	'ActionWebhook:requestparameters' => 'Paramètres de la requête',
	'ActionWebhook:advancedparameters' => 'Paramètres avancés',
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
	'Class:ActioniTopWebhook/Attribute:payload+' => 'La charge utile JSON, doit être une chaine JSON contenant le nom de l\'opération et ses paramètres, voir la documentation pour plus d\'informations',
	// - Fieldsets
	'ActioniTopWebhook:requestparameters' => 'Paramètres de la requêtre',
));

// Slack
Dict::Add('FR FR', 'French', 'Français', array(
	// ActionSlackNotification
	'Class:ActionSlackNotification' => 'Notification Slack',
	'Class:ActionSlackNotification+' => 'Envoi une notification sous forme de message dans un canal ou à un utilisateur Slack',
	'Class:ActionSlackNotification/Attribute:message' => 'Message',
	'Class:ActionSlackNotification/Attribute:include_list_attributes' => 'Attributs de',
	'Class:ActionSlackNotification/Attribute:include_list_attributes+' => 'Affiche des attributs additionels de l\'objet ayant déclenché l\'action sous le message. Ils correspondent aux attributs soit à ceux de la vue liste habituelle ou d\'une vue \'slack\' personnalisée. Note : La vue \'slack\' doit être définie dans le modèle de données au préalable (zlist)',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:list' => 'la vue liste habituelle',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:slack' => 'la vue personnalisée "slack"',
	'Class:ActionSlackNotification/Attribute:include_user_info' => 'Info. utilisateur',
	'Class:ActionSlackNotification/Attribute:include_user_info+' => 'Affiche les infos (nom complet) de l\'utilisateur courant sous le message',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:no' => 'Non',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:yes' => 'Oui',
	'Class:ActionSlackNotification/Attribute:include_modify_button' => 'Bouton modifier',
	'Class:ActionSlackNotification/Attribute:include_modify_button+' => 'Ajoute un bouton sous le message pour modifier l\'objet correspondant dans '.ITOP_APPLICATION_SHORT,
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:no' => 'Non',
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:yes' => 'Oui',
	'Class:ActionSlackNotification/Attribute:include_delete_button' => 'Bouton supprimer',
	'Class:ActionSlackNotification/Attribute:include_delete_button+' => 'Ajoute un bouton sous le message pour supprimer l\'objet correspondant dans '.ITOP_APPLICATION_SHORT,
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:no' => 'Non',
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:yes' => 'Oui',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button' => 'Autres boutons d\'actions',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button+' => 'Ajoute d\'autres actions (comme les transitions possibles dans l\'état courant) sous le message',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:no' => 'Non',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:specify' => 'A spécifier',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:yes' => 'Oui',
	'Class:ActionSlackNotification/Attribute:specified_other_actions' => 'Autres codes d\'actions',
	'Class:ActionSlackNotification/Attribute:prepare_payload_callback+' => 'Méthode PHP pour préparer les données de la charge utile à envoyer lors de l\'appel du webhook. Utiliser ce champ si les options standard ne sont pas assez flexibles ou si la structure doit être construite dynamiquement.

Vous pouvez utiliser 2 types de méthodes :
- Méthode de l\'objet déclenchant l\'action (ex : Demande utilisateur), doit être publique. Exemple : $this->XXX($aContextArgs, $oLog, $oAction)
- Méthode de n\'importe quelle classe PHP, doit être statique ET publique. Le nom doit être entièrement qualifié (inclure le namespace). Exemple: \UneClasse::XXX($oObject, $aContextArgs, $oLog, $oAction)

IMPORTANT : Si renseigné, les champs \'message\' et tous les \'éléments additionels\' seront ignorés.',
	// - Fieldsets
	'ActionSlackNotification:message' => 'Message de base',
	'ActionSlackNotification:additionalelements' => 'Eléments additionnels à inclure',

	// Payload
	'ActionSlackNotification:Payload:BlockKit:UserInfo' => 'Notification de <%2$s|%1$s> (%3$s)',
));

// Rocket.Chat
Dict::Add('FR FR', 'French', 'Français', array(
	// ActionRocketChatNotification
	'Class:ActionRocketChatNotification' => 'Notification Rocket.Chat',
	'Class:ActionRocketChatNotification+' => 'Envoi une notification sous forme de message dans un canal ou à un utilisateur Rocket.Chat',
	'Class:ActionRocketChatNotification/Attribute:message' => 'Message',
	'Class:ActionRocketChatNotification/Attribute:message+' => 'Message qui sera affiché dans le chat',
	'Class:ActionRocketChatNotification/Attribute:bot_alias' => 'Alias',
	'Class:ActionRocketChatNotification/Attribute:bot_alias+' => 'Remplace l\'alias par défaut du bot, apparaîtra devant le nom d\'utilisateur du message',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar' => 'Avatar image',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar+' => 'Remplace l\'avatar par défaut du bot, doit être une URL absolue de l\'image à utiliser',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar' => 'Avatar emoji',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar+' => 'Remplace l\'avatar par défaut du bot, peut être n\'importe quel emojis de Rocket.Chat (ex : :ghost:, :white_check_mark:, ...). Note : si une "avatar image" est renseigné, l\'emoji ne sera pas affiché.',
	'Class:ActionRocketChatNotification/Attribute:prepare_payload_callback+' => 'Méthode PHP pour préparer les données de la charge utile à envoyer lors de l\'appel du webhook. Utiliser ce champ si les options standard ne sont pas assez flexibles ou si la structure doit être construite dynamiquement.

Vous pouvez utiliser 2 types de méthodes :
- Méthode de l\'objet déclenchant l\'action (ex : Demande utilisateur), doit être publique. Exemple : $this->XXX($aContextArgs, $oLog, $oAction)
- Méthode de n\'importe quelle classe PHP, doit être statique ET publique. Le nom doit être entièrement qualifié (inclure le namespace). Exemple: \UneClasse::XXX($oObject, $aContextArgs, $oLog, $oAction)

IMPORTANT : Si renseigné, les champs \'message\' et toutes les \'informations du bot\' seront ignorés.',
	// - Fieldsets
	'ActionRocketChatNotification:message' => 'Message de base',
	'ActionRocketChatNotification:additionalelements' => 'Informations du bot'
));

// Google Chat
Dict::Add('FR FR', 'French', 'Français', array(
	// ActionGoogleChatNotification
	'Class:ActionGoogleChatNotification' => 'Notification Google Chat',
	'Class:ActionGoogleChatNotification+' => 'Envoi une notification sous forme de message dans un espace Google Chat',
	'Class:ActionGoogleChatNotification/Attribute:message' => 'Message',
	'Class:ActionGoogleChatNotification/Attribute:message+' => 'Message qui sera affiché dans le chat, seul le text brut est supporté pour le moment.',
	'Class:ActionGoogleChatNotification/Attribute:prepare_payload_callback+' => 'Méthode PHP pour préparer les données de la charge utile à envoyer lors de l\'appel du webhook. Utiliser ce champ si les options standard ne sont pas assez flexibles ou si la structure doit être construite dynamiquement.

Vous pouvez utiliser 2 types de méthodes :
- Méthode de l\'objet déclenchant l\'action (ex : Demande utilisateur), doit être publique. Exemple : $this->XXX($aContextArgs, $oLog, $oAction)
- Méthode de n\'importe quelle classe PHP, doit être statique ET publique. Le nom doit être entièrement qualifié (inclure le namespace). Exemple: \UneClasse::XXX($oObject, $aContextArgs, $oLog, $oAction)

IMPORTANT : Si renseigné, le champ \'message\' sera ignoré.',
	// - Fieldsets
	'ActionGoogleChatNotification:message' => 'Message',
));

// Microsoft Teams
Dict::Add('FR FR', 'French', 'Français', array(
	// ActionMicrosoftTeamsNotification
	'Class:ActionMicrosoftTeamsNotification' => 'Notification Microsoft Teams',
	'Class:ActionMicrosoftTeamsNotification+' => 'Envoi une notification sous forme de message dans un canal Microsoft Teams',
	'Class:ActionMicrosoftTeamsNotification/Attribute:title' => 'Titre',
	'Class:ActionMicrosoftTeamsNotification/Attribute:message' => 'Message',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes' => 'Attributs de',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes+' => 'Affiche des attributs additionels de l\'objet ayant déclenché l\'action sous le message. Ils correspondent aux attributs soit à ceux de la vue liste habituelle ou d\'une vue \'msteams\' personnalisée. Note : La vue \'msteams\' doit être définie dans le modèle de données au préalable (zlist)',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:list' => 'la vue liste habituelle',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:msteams' => 'la vue personnalisée "msteams"',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button' => 'Bouton modifier',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button+' => 'Ajoute un bouton sous le message pour modifier l\'objet correspondant dans '.ITOP_APPLICATION_SHORT,
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:no' => 'Non',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:yes' => 'Oui',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button' => 'Bouton supprimer',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button+' => 'Ajoute un bouton sous le message pour supprimer l\'objet correspondant dans '.ITOP_APPLICATION_SHORT,
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:no' => 'Non',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:yes' => 'Oui',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button' => 'Autres boutons d\'actions',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button+' => 'Ajoute d\'autres actions (comme les transitions possibles dans l\'état courant) sous le message',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:no' => 'Non',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:specify' => 'A spécifier',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:yes' => 'Oui',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions' => 'Autres codes d\'actions',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions+' => 'Spécifie les autres actions à ajouter comme boutons sous le message. Doit être liste de code d\{actions séparés par des virgules (ex : \'ev_reopen, ev_close\')',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color' => 'Couleur du liseret',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color+' => 'Couleur du liseret du message dans Microsft Teams, doit être une couleur hexadecimale valide (ex : FF0000)',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url' => 'Image en médallion',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url+' => 'URL de l\'image à afficher comme médaillon du message, elle doit être accessible publiquement sur Internet pour que Microsoft Teams puisse l\'afficher',
	'Class:ActionMicrosoftTeamsNotification/Attribute:prepare_payload_callback+' => 'Méthode PHP pour préparer les données de la charge utile à envoyer lors de l\'appel du webhook. Utiliser ce champ si les options standard ne sont pas assez flexibles ou si la structure doit être construite dynamiquement.
	
Vous pouvez utiliser 2 types de méthodes :
- Méthode de l\'objet déclenchant l\'action (ex : Demande utilisateur), doit être publique. Exemple : $this->XXX($aContextArgs, $oLog, $oAction)
- Méthode de n\'importe quelle classe PHP, doit être statique ET publique. Le nom doit être entièrement qualifié (inclure le namespace). Exemple: \UneClasse::XXX($oObject, $aContextArgs, $oLog, $oAction)

IMPORTANT : Si renseigné, les champs \'titre\', \'message\' et tous les \'éléments additionnels\' seront ignorés.',
	// - Fieldsets
	'ActionMicrosoftTeamsNotification:message' => 'Message de base',
	'ActionMicrosoftTeamsNotification:additionalelements' => 'Eléments additionnels à inclure',
	'ActionMicrosoftTeamsNotification:theme' => 'Thème',
));
