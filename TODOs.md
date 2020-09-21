## Questions
- [X] Why not put the credentials / token in the WebhookURL instead of the Action? \
=> We are doing it, derivated classes.
- [X] Should we handle XMLRPC webhooks as well? \
=> Not until we have a really use case / need for it.

## TODOs
- [ ] Struct data to load RemoteApplicationType objects

## Webhook call (generic)
- [ ] Attribute to choose the GUI (backoffice, itop-portal, ...) the hyperlinks should point to? \
=> Find a way to fill a dropdown with the content of the PortalDispatcher
- [ ] How to handle response properly?
    - [ ] Add object_class / object_id to async_task so we can retrieve object afterwards
    - [ ] Put the response callback in the WebRequest itself?
- [ ] Split response callback in 2: Success callback / Failure callback
- [ ] How to filter webhook URLs based on the action class? (eg. for _Slack notification_ only show webhook URLs for _Generic_ and _Slack_ applications)
- [ ] Add option to log payload or not in the eventnotification (to avoid storing password)
- [ ] Add option to log event in a log file in addition to the eventnotification

## iTop webhook
Done

## Slack notification
- [ ] Attachment
- [ ] Review "other action" codes and names

## Rocket chat notification
- [ ] Field for an attachment (several?)

## Google Chat notification
- [X] Test on Euromaster server
- [ ] Action class
- [ ] Action class icon

## Tuleap webhook
- [ ] Action class icon
- [ ] Preset response methods to do stuff (Go back to previous state for example)
- [X] Preset operation Create artifact
- [ ] Preset operation Update artifact
- [X] Hide API key in log
- [X] Remote app. connection
- [X] API Key: tlp-k1-7.b96952c2b1a52aa0582070dc3877d30a2556defcfd4cf3470170034888b4cef4

## Other integration candidates
- Jira webhook
- Jenkins webhook
- Google Chat notification
- Telegram notification
- Mattermott notification
- GitHub
