<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 *
 */
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', [
	'ActionGoogleChatNotification:message' => 'Mensaje',
	'ActionMicrosoftTeamsNotification:additionalelements' => 'Additional elements to include~~',
	'ActionMicrosoftTeamsNotification:message' => 'Basis message~~',
	'ActionMicrosoftTeamsNotification:theme' => 'Theme~~',
	'ActionRocketChatNotification:additionalelements' => 'Información Bot',
	'ActionRocketChatNotification:message' => 'Mensaje base',
	'ActionSlackNotification:Payload:BlockKit:UserInfo' => 'Notificación de <%2$s|%1$s> (%3$s)',
	'ActionSlackNotification:additionalelements' => 'Elementos adicionales a incluir',
	'ActionSlackNotification:message' => 'Mensaje base',
	'ActionWebhook:advancedparameters' => 'Parámetros Avanzados',
	'ActionWebhook:baseinfo' => 'Información General',
	'ActionWebhook:moreinfo' => 'Más información',
	'ActionWebhook:requestparameters' => 'Parámetros de Solicitud',
	'ActionWebhook:webhookconnection' => 'Conexión Webhook',
	'Class:ActionGoogleChatNotification' => 'Notificación Google Chat',
	'Class:ActionGoogleChatNotification+' => 'Send a notification as a Google Chat message in a space~~',
	'Class:ActionGoogleChatNotification/Attribute:message' => 'Mensaje',
	'Class:ActionGoogleChatNotification/Attribute:message+' => 'Message that will be displayed in the chat, only plain text is supported for now.~~',
	'Class:ActionGoogleChatNotification/Attribute:prepare_payload_callback+' => 'PHP method to prepare payload data to be sent during the webhook call. Use this if the standard options are not flexible enough or if your payload structure must be dynamically built.

Puede utilizar 2 tipos de métodos:
- Desde el propio objeto disparador (por ejemplo, UserRequest), debe ser público. Ejemplo: $this->XXX($aContextArgs, $oLog, $oAction)
- Desde cualquier clase PHP, debe ser estático Y público. El nombre debe ser un nombre completamente calificado. Ejemplo: \AlgunaClase::XXX($oObjeto, $aContextArgs, $oLog, $oAcción)
	
IMPORTANTE: Si se habilita, el \'mensaje\' será ignorados.',
	'Class:ActionMicrosoftTeamsNotification' => 'Notificación Microsoft Teams',
	'Class:ActionMicrosoftTeamsNotification+' => 'Send a notification as a Microsoft Teams message in a channel~~',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url' => 'Medallion picture~~',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url+' => 'URL of the image to display as a medallion in the message card, it must be publicly accessible on the internet for Microsoft Teams to be able to display it~~',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button' => 'Botón Borrar',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button+' => 'Include a button below the message to delete the object in '.ITOP_APPLICATION_SHORT.'~~',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:no' => 'No',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:yes' => 'Si',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes' => 'Atributos de',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes+' => 'Desplegar atributos adicionales debajo del mensaje. They can be either from the usual \'list\' view or the custom \'msteams\' view of the object triggering the notification . Note that the \'msteams\' view must be defined in the datamodel first (zlist)',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:list' => 'the usual list view~~',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:msteams' => 'the custom "msteams" view~~',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button' => 'Botón Modificar',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button+' => 'Include a button below the message to edit the object in '.ITOP_APPLICATION_SHORT.'~~',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:no' => 'No',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:yes' => 'Si',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button' => 'Botones de otras acciones',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button+' => 'Include other actions (such as transitions available in the current state) below the message~~',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:no' => 'No',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:specify' => 'Especificar',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:yes' => 'Si',
	'Class:ActionMicrosoftTeamsNotification/Attribute:message' => 'Mensaje',
	'Class:ActionMicrosoftTeamsNotification/Attribute:prepare_payload_callback+' => 'PHP method to prepare payload data to be sent during the webhook call. Use this if the standard options are not flexible enough or if your payload structure must be dynamically built.

Puede utilizar 2 tipos de métodos:
- Desde el propio objeto disparador (por ejemplo, UserRequest), debe ser público. Ejemplo: $this->XXX($aContextArgs, $oLog, $oAction)
- Desde cualquier clase PHP, debe ser estático Y público. El nombre debe ser un nombre completamente calificado. Ejemplo: \AlgunaClase::XXX($oObjeto, $aContextArgs, $oLog, $oAcción)
	
IMPORTANTE: Si se habilita, el \'title\', \'mensaje\' y todos los \'elementos adicionales\' serán ignorados.',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions' => 'Códigos de otras acciones',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions+' => 'Specify which actions to include as buttons below the message. Should be a comma separated list of the actions codes (eg. \'ev_reopen, ev_close\')~~',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color' => 'Highlight color~~',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color+' => 'Highlight color of the message card in Microsoft Teams, must be a valid hexadecimal color (eg. FF0000)~~',
	'Class:ActionMicrosoftTeamsNotification/Attribute:title' => 'Title~~',
	'Class:ActionRocketChatNotification' => 'Notificaciones Rocket.Chat',
	'Class:ActionRocketChatNotification+' => 'Send a notification as a Rocket.Chat message in a channel or to a user~~',
	'Class:ActionRocketChatNotification/Attribute:bot_alias' => 'Alias',
	'Class:ActionRocketChatNotification/Attribute:bot_alias+' => 'Overrides the default bot alias, will appear before the username of the message~~',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar' => 'Emoji avatar',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar+' => 'Overrides the default bot avatar, can be any Rocket.Chat emojis (eg. :ghost:, :white_check_mark:, ...). Note that if an URL avatar is set, the emoji won\'t be displayed.~~',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar' => 'Imagen avatar',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar+' => 'Overrides the default bot avatar, must be an absolute URL to the image to use~~',
	'Class:ActionRocketChatNotification/Attribute:message' => 'Mensaje',
	'Class:ActionRocketChatNotification/Attribute:message+' => 'Message that will be displayed in the chat~~',
	'Class:ActionRocketChatNotification/Attribute:prepare_payload_callback+' => 'PHP method to prepare payload data to be sent during the webhook call. Use this if the standard options are not flexible enough or if your payload structure must be dynamically built.

Puede utilizar 2 tipos de métodos:
- Desde el propio objeto disparador (por ejemplo, UserRequest), debe ser público. Ejemplo: $this->XXX($aContextArgs, $oLog, $oAction)
- Desde cualquier clase PHP, debe ser estático Y público. El nombre debe ser un nombre completamente calificado. Ejemplo: \AlgunaClase::XXX($oObjeto, $aContextArgs, $oLog, $oAcción)
	
IMPORTANTE: Si se habilita, el \'mensaje\' y toda la \'información bot\' serán ignorados.',
	'Class:ActionSlackNotification' => 'Notificación Slack',
	'Class:ActionSlackNotification+' => 'Envar una notificación como un mensaje Slack en un canal o a un usuario',
	'Class:ActionSlackNotification/Attribute:include_delete_button' => 'Botón Borrar',
	'Class:ActionSlackNotification/Attribute:include_delete_button+' => 'Include a button below the message to delete the object in '.ITOP_APPLICATION_SHORT.'~~',
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:no' => 'No',
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:yes' => 'Si',
	'Class:ActionSlackNotification/Attribute:include_list_attributes' => 'Atributos de',
	'Class:ActionSlackNotification/Attribute:include_list_attributes+' => 'Desplegar atributos adicionales debajo del mensaje. They can be either from the usual \'list\' view or the custom \'slack\' view of the object triggering the notification . Note that the \'slack\' view must be defined in the datamodel first (zlist)',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:list' => 'the usual list view~~',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:slack' => 'the custom "slack" view~~',
	'Class:ActionSlackNotification/Attribute:include_modify_button' => 'Botón Modificar',
	'Class:ActionSlackNotification/Attribute:include_modify_button+' => 'Include a button below the message to edit the object in '.ITOP_APPLICATION_SHORT.'~~',
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:no' => 'No',
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:yes' => 'Si',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button' => 'Botones de otras acciones',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button+' => 'Include other actions (such as transitions available in the current state) below the message~~',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:no' => 'No',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:specify' => 'Especificar',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:yes' => 'Si',
	'Class:ActionSlackNotification/Attribute:include_user_info' => 'Info. Usuario',
	'Class:ActionSlackNotification/Attribute:include_user_info+' => 'Desplegar información de usuario (nombre completo) debajo del mensaje',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:no' => 'No',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:yes' => 'Si',
	'Class:ActionSlackNotification/Attribute:message' => 'Mensaje',
	'Class:ActionSlackNotification/Attribute:prepare_payload_callback+' => 'PHP method to prepare payload data to be sent during the webhook call. Use this if the standard options are not flexible enough or if your payload structure must be dynamically built.

Puede utilizar 2 tipos de métodos:
- Desde el propio objeto disparador (por ejemplo, UserRequest), debe ser público. Ejemplo: $this->XXX($aContextArgs, $oLog, $oAction)
- Desde cualquier clase PHP, debe ser estático Y público. El nombre debe ser un nombre completamente calificado. Ejemplo: \AlgunaClase::XXX($oObjeto, $aContextArgs, $oLog, $oAcción)
	
IMPORTANTE: Si se habilita, el \'mensaje\' y todos los \'elementos adicionales\' serán ignorados.',
	'Class:ActionSlackNotification/Attribute:specified_other_actions' => 'Códigos de otras acciones',
	'Class:ActionSlackNotification/Attribute:specified_other_actions+' => 'Specify which actions to include as buttons below the message. Should be a comma separated list of the actions codes (eg. \'ev_reopen, ev_close\')~~',
	'Class:ActionWebhook' => 'Llamada Webhook (genérica)',
	'Class:ActionWebhook+' => 'Llamada Webhook para cualquier tipo de aplicación',
	'Class:ActionWebhook/Attribute:asynchronous+' => 'Whether this action should be executed in background or not (mind that global setting for webhook actions is the "prefer_asynchronous" conf. parameter of the "combodo-webhook-action" module)~~',
	'Class:ActionWebhook/Attribute:headers' => 'Encabezados',
	'Class:ActionWebhook/Attribute:headers+' => 'Encabezados de la solicitud HTTP, debe ser uno por línea (ejem. \'Content-type: application/json\')',
	'Class:ActionWebhook/Attribute:language' => 'Idioma',
	'Class:ActionWebhook/Attribute:language+' => 'Idioma de esta notificación, que se usa principalmente al buscar notificaciones, pero también se puede usar para traducir la etiqueta de atributos',
	'Class:ActionWebhook/Attribute:method' => 'Método',
	'Class:ActionWebhook/Attribute:method+' => 'Método de la solicitud HTTP',
	'Class:ActionWebhook/Attribute:method/Value:delete' => 'DELETE',
	'Class:ActionWebhook/Attribute:method/Value:get' => 'GET',
	'Class:ActionWebhook/Attribute:method/Value:head' => 'HEAD~~',
	'Class:ActionWebhook/Attribute:method/Value:patch' => 'PATCH',
	'Class:ActionWebhook/Attribute:method/Value:post' => 'POST',
	'Class:ActionWebhook/Attribute:method/Value:put' => 'PUT',
	'Class:ActionWebhook/Attribute:path' => 'Path~~',
	'Class:ActionWebhook/Attribute:path+' => 'Additional path to append to the connection URL (eg. \'/some/specific-endpoint\')~~',
	'Class:ActionWebhook/Attribute:payload' => 'Carga',
	'Class:ActionWebhook/Attribute:payload+' => 'Datos enviados durante la llamada webhook, muchas de las veces es una cadena JSON. Uselo si su estructura de carga es estática.

	IMPORTANTE: Se ignorará si se habilita \'Preparar devolución de llamada de carga\'',
	'Class:ActionWebhook/Attribute:prepare_payload_callback' => 'Preparar devolución de llamada de carga',
	'Class:ActionWebhook/Attribute:prepare_payload_callback+' => 'Método PHP para preparar los datos de carga que se enviarán durante la llamada del webhook. Use esto si su estructura de carga debe construirse dinámicamente.

Puede utilizar 2 tipos de métodos:
- Desde el propio objeto disparador (por ejemplo, UserRequest), debe ser público. Ejemplo: $this->XXX($aContextArgs, $oLog, $oAction)
- Desde cualquier clase PHP, debe ser estático Y público. El nombre debe ser un nombre completamente calificado. Ejemplo: \AlgunaClase::XXX($oObjeto, $aContextArgs, $oLog, $oAcción)

IMPORTANTE: Si se habilita, se ignorará el atributo \'Carga\'.',
	'Class:ActionWebhook/Attribute:process_response_callback' => 'Procesar llamada de respuesta',
	'Class:ActionWebhook/Attribute:process_response_callback+' => 'Método PHP para procesar la respuesta de llamada de webhook.

Puede utilizar 2 tipos de métodos:
- Desde el propio objeto disparador (por ejemplo, UserRequest), debe ser público. Ejemplo: $this->XXX($aContextArgs, $oLog, $oAction)
- Desde cualquier clase PHP, debe ser estático Y público. El nombre debe ser un nombre completamente calificado. Ejemplo: \AlgunaClase::XXX($oObjeto, $aContextArgs, $oLog, $oAcción)
- $oResponse puede ser nulo en algunos casos (ejem. request failed to send)',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id' => 'Conexión',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id+' => 'Información de conexión para usar cuando el estado es \'en producción\'',
	'Class:ActionWebhook/Attribute:remoteapplicationtype_action_explanation' => 'Explicación',
	'Class:ActionWebhook/Attribute:remoteapplicationtype_action_explanation+' => 'Explicación sobre cómo configurar esta acción para la aplicación de la conexión. Por ejemplo, cómo construir la carga útil o qué esperar en la respuesta.',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id' => 'Conexión de prueba',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id+' => 'Información de conexión para usar cuando el estado está \'en prueba\'',
	'Class:ActioniTopWebhook' => 'Llamada iTop webhook',
	'Class:ActioniTopWebhook+' => 'Llamada webhook a una aplicación iTop remota',
	'Class:ActioniTopWebhook/Attribute:headers+' => 'Los encabezados de la solicitud HTTP deben ser uno por línea (por ejemplo, \'Content-type: application/x-www-form-urlencoded\')

IMPORTANTE:
- \'Content-type\' debe establecerse en \'application/x-www-form-urlencoded\' para iTop, aunque enviemos JSON
- Un encabezado \'Autorización básica\' se agregará automáticamente a la solicitud durante el envío, que contiene las credenciales de la conexión seleccionada',
	'Class:ActioniTopWebhook/Attribute:payload' => 'datos JSON',
	'Class:ActioniTopWebhook/Attribute:payload+' => 'La carga JSON debe ser una cadena JSON que contenga el nombre de la operación y los parámetros. Consulte la documentación para obtener información detallada.',
	'Class:ActioniTopWebhook/Attribute:prepare_payload_callback+' => 'Método PHP para preparar los datos de carga que se enviarán durante la llamada webhook. Use esto si su estructura de carga debe construirse dinámicamente.

Puede utilizar 2 tipos de métodos:
- Desde el propio objeto disparador (por ejemplo, UserRequest), debe ser público. Ejemplo: $this->XXX($aContextArgs, $oLog, $oAction)
- Desde cualquier clase PHP, debe ser estático Y público. El nombre debe ser un nombre completamente calificado. Ejemplo: \AlgunaClase::XXX($oObjeto, $aContextArgs, $oLog, $oAcción)

IMPORTANTE: Si se habilita, el atributo \'JSON data\' será ignorado.',
	'Class:EventWebhook' => 'Evento de emisión Webhook',
	'Class:EventWebhook/Attribute:action_finalclass' => 'Clase Final',
	'Class:EventWebhook/Attribute:headers' => 'Encabezados',
	'Class:EventWebhook/Attribute:payload' => 'Carga',
	'Class:EventWebhook/Attribute:response' => 'Respuesta',
	'Class:EventWebhook/Attribute:webhook_url' => 'URL Webhook URL',
	'Class:RemoteApplicationConnection' => 'Conexión de aplicación remota',
	'Class:RemoteApplicationConnection/Attribute:actions_list' => 'Notificaciones Webhook',
	'Class:RemoteApplicationConnection/Attribute:actions_list+' => 'Notificacioens usadas por esta conexión Webhook',
	'Class:RemoteApplicationConnection/Attribute:environment' => 'Ambiente',
	'Class:RemoteApplicationConnection/Attribute:environment+' => 'Tipo de ambiente de la conexión',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:1-development' => 'Desarrollo',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:2-test' => 'Pruebas',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:3-production' => 'Producción',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id' => 'Tipo de aplicación',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id+' => 'Tipo de aplicación para la que es la conexión (use \'Genérico\' si la suya no está en la lista)',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_remoteapplicationconnection_explanation' => 'Explicación',
	'Class:RemoteApplicationConnection/Attribute:url' => 'URL',
	'Class:RemoteApplicationType' => 'Tipo de aplicación remota',
	'Class:RemoteApplicationType/Attribute:action_explanation' => 'Explicación de la acción',
	'Class:RemoteApplicationType/Attribute:action_explanation+' => 'Explicación sobre cómo configurar una acción webhook en '.ITOP_APPLICATION_SHORT.' (activada por un disparador) para esa aplicación. Por ejemplo, cómo construir una carga o qué esperar en la respuesta.',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnection_explanation' => 'Explicación de la conexión',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnection_explanation+' => 'Explicación sobre cómo configurar una conexión para esa aplicación, por ejemplo, dónde y cómo crear el punto final',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list' => 'Conexiones',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list+' => 'Conexiones para esta aplicación',
	'Class:RemoteiTopConnection' => 'Conexión remota iTop',
	'Class:RemoteiTopConnection/Attribute:auth_pwd' => 'Contraseña Aut.',
	'Class:RemoteiTopConnection/Attribute:auth_pwd+' => 'Contraseña del usuario (en el iTop remoto) utilizada para la autenticación',
	'Class:RemoteiTopConnection/Attribute:auth_user' => 'Usuario Aut.',
	'Class:RemoteiTopConnection/Attribute:auth_user+' => 'Inicio de sesión del usuario (en el iTop remoto) utilizado para la autenticación',
	'Class:RemoteiTopConnection/Attribute:version' => 'Versión de API',
	'Class:RemoteiTopConnection/Attribute:version+' => 'Versión de la API llamada (por ejemplo, 1.3)',
	'Class:RemoteiTopConnectionToken' => 'Remote iTop connection using a Token~~',
	'Class:RemoteiTopConnectionToken/Attribute:token+' => 'Token~~',
	'Dashboard:Integrations:ActionWebhookList:Title' => 'Acciones de tipo webhook',
	'Dashboard:Integrations:Outgoing:Title' => 'Integraciones webhook salientes',
	'Dashboard:Integrations:Title' => 'Integraciones con aplicacioens externas',
	'Menu:Webhook' => 'Webhooks~~',
	'RemoteApplicationConnection:authinfo' => 'Autenticación',
	'RemoteApplicationConnection:baseinfo' => 'Información General',
	'RemoteApplicationConnection:moreinfo' => 'Más information',
]);
