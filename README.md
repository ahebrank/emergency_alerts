CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Configuration
 * Maintainers


INTRODUCTION
------------

The Emergency Alerts module provides emergency alerts. These are the important
announcements you might see at the top of academic sites.

 * For a full description of the module, visit the project page:
   https://www.drupal.org/project/emergency_alerts

 * To submit bug reports and feature suggestions, or to track changes:
   https://www.drupal.org/project/issues/emergency_alerts


REQUIREMENTS
------------

This module requires no modules outside of Drupal core.


INSTALLATION
------------

 * Install the Emergency Alerts module as you would normally install a
   contributed Drupal module. Visit https://www.drupal.org/node/1897420 for
   further information.


CONFIGURATION
-------------

    1. Navigate to Administration > Extend and enable the module.
    2. Navigate to admin/config/emergency_alerts to configure.
    3. Navigate to admin/structure/block to place the block.

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


MAINTAINERS
-----------

 * Andy Hebrank (ahebrank) - https://www.drupal.org/u/ahebrank

Supporting organization:
 * New City - https://www.drupal.org/newcity
