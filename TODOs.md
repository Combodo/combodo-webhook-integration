## Questions
- [ ] Display ActionWebhooks in the "Integrations" page rather than "Notification" which is more email-oriented?
- [ ] Test if we can retrieve attachments in the PrepareRequest part

## TODOs
- [ ] Multiple line attributes (eg. UserRequest:description) don't work for now
- [ ] Struct data to load RemoteApplicationType objects
- [ ] Document internally dev / test envs (Rocket, Slack, ...)
- [ ] Check to write to ensure there is a "test connection" when status is "test"

## Limitations
- Webhook actions do not work with the TriggerOnLogUpdate trigger as it is restricted by "email-reply" to email actions only.
- If network error, response callback is not called. Seems ok, but is it? => Seen with Erwan, it's ok.
- Google Chat action only works with Google Workspace (paid version)

## Webhook call (generic)
- [ ] Attribute to choose the GUI (backoffice, itop-portal, ...) the hyperlinks should point to? \
=> Find a way to fill a dropdown with the content of the PortalDispatcher
- [ ] How to handle response properly?
    - [ ] Add object_class / object_id to async_task so we can retrieve object afterwards
    - [ ] Put the response callback in the WebRequest itself?
- [ ] Split response callback in 2: Success callback / Failure callback
- [ ] How to filter webhook URLs based on the action class? (eg. for _Slack notification_ only show webhook URLs for _Generic_ and _Slack_ applications)

## iTop webhook

## Slack notification
- [ ] Review "other action" codes and names

## Rocket chat notification

## Google Chat notification
- [ ] Action class
- [ ] Action class icon

## Other integration candidates
- Jira webhook
- Jenkins webhook
- Telegram notification
- Mattermott notification
- GitHub
