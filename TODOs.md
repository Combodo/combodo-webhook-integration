## Limitations
- Webhook actions do not work with the TriggerOnLogUpdate trigger as it is restricted by "email-reply" to email actions only.
- If asynchronous sending, total request cannot exceed 64MB when serialized, so URL + headers + payload should not excess 60MB (eg. if sending attachments).
- If network error, response callback is not called. Seems ok, but is it? => Seen with Erwan, it's ok.
- Google Chat action only works with Google Workspace (paid version)

## Other integration candidates
- Jira webhook
- Jenkins webhook
- Telegram notification
- Mattermott notification
- GitHub
