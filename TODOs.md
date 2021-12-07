## Questions
- [ ] Make abstract classes to distinguish chat webhook from app integration webhooks?
- [X] Display ActionWebhooks in the "Integrations" page rather than "Notification" which is more email-oriented?
- [X] Test if we can retrieve attachments in the PrepareRequest part

## TODOs

## Limitations
- Webhook actions do not work with the TriggerOnLogUpdate trigger as it is restricted by "email-reply" to email actions only.
- If network error, response callback is not called. Seems ok, but is it? => Seen with Erwan, it's ok.
- Google Chat action only works with Google Workspace (paid version)

## Webhook call (generic)
- [ ] Attribute to choose the GUI (backoffice, itop-portal, ...) the hyperlinks should point to? \
=> Find a way to fill a dropdown with the content of the PortalDispatcher
- [ ] How to filter webhook URLs based on the action class? (eg. for _Slack notification_ only show webhook URLs for _Generic_ and _Slack_ applications)

## Slack notification
- [ ] Review "other action" codes and names

## Other integration candidates
- Jira webhook
- Jenkins webhook
- Telegram notification
- Mattermott notification
- GitHub
