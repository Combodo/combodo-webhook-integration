# Generic
- Example of payload callback:
    - Make a snippet like:
       ```
       class WebhookPayloadHandler
        {
            public static function MyPayloadPreparation($aContextArgs, $oLog)
            {
                $oObject = $aContextArgs['this->object()'];
        
                $aData = array(
                    'title' => $oObject->GetName(),
                    'description' => 'The object was updated',
                );
        
                return json_encode($aData);
            }
        }
        ```
    - Put it in the "Prepare payload callback" attribute: `\WebhookPayloadHandler::MyPayloadPreparation`

# Slack
- MUST have a slack app. Put link to references
- Reference to make payload:
    - Block kit: https://api.slack.com/block-kit/building
    - Block builder: https://app.slack.com/block-kit-builder/