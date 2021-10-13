## Questions
- [ ]

## TODOs
- [ ] Struct data to load RemoteApplicationType objects
- [ ] Add $oObject-> as a possibility for response callback

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
- [ ] Transform authentication params to Basic Auth to avoid necessity to add "url" as a login mode

## Slack notification
- [ ] Review "other action" codes and names

## Rocket chat notification

## Google Chat notification
- [X] Test on Euromaster server
- [ ] Action class
- [ ] Action class icon

## Tuleap webhook
- [ ] Action class icon
- [ ] Preset response methods to do stuff (Go back to previous state for example)
- [X] Preset operation Create artifact
- [ ] Preset operation Update artifact
- [X] Remote app. connection
- [X] API Key support
- [X] Hide API key in log
- [ ] Specific integration (other extension)
    - [ ] Way to define which attrbiutes in Tuleap carry the linked iTop object class/ID so it can be send back to iTop for Tuleap => iTop calls

## Other integration candidates
- Jira webhook
- Jenkins webhook
- Telegram notification
- Mattermott notification
- GitHub
