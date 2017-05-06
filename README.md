# Emergency Alerts

A Drupal 8 module to provide emergency alerts.

## Quickstart

Enable this module and configure to set alert title and message,
severity, and to turn on block or full page override.

(optional) Add region `emergency_alert` to your theme and in templates.
For example (in theme.info.yml),

```yml
regions:
  content: Content
  sidebar: Sidebar
  footer: Footer Nav
  emergency_alert: 'Emergency alert'
```

Place the `Emergency Alert` block created by this module 
in the region you added above or in any other region.

Copy template `html--emergency-alert.html.twig` (for full-page alert) to your 
theme and edit (but keep the `page.emergency_alert` region)

Copy template `emergency-alert.html.twig` to your theme and edit as needed.

Add styling for the alert region div with classes:

- `.emergency-alert.announcement`
- `.emergency-alert.warning`
- `.emergency-alert.danger`
