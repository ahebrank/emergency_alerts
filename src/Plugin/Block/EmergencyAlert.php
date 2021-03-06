<?php

namespace Drupal\emergency_alerts\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;

/**
 * Provides an 'EmergencyAlert' Block.
 *
 * @Block(
 *   id = "emergency_alert",
 *   admin_label = @Translation("Emergency Alert"),
 * )
 */
class EmergencyAlert extends BlockBase {

  public $configKey = 'emergency_alerts.settings';

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::config($this->configKey);

    // Get config variables.
    $message = $config->get('alert_message', FALSE);
    $level = $config->get('alert_level', 'announcement');

    // Check for a cookie.
    $cookies = \Drupal::request()->cookies;
    $cookie_set = FALSE;
    if ($cookies->has('emergency-alert')) {
      $cookie_set = ($cookies->get('emergency-alert') == 'closed');
    }

    // Should alert be shown?
    $show_alert = $config->get('show_block') && !$cookie_set;

    $message_render = [];
    if ($message) {
      $message_render = check_markup($message['value'], $message['format']);
    }

    $build = [
      '#access' => $show_alert,
      '#theme' => 'emergency_alert',
      '#title' => $config->get('alert_title'),
      '#message' => $message_render,
      '#alert_level' => $level,
      '#attached' => [
        'library' => [
          'emergency_alerts/persist_close',
        ],
      ],
    ];

    // Add a dependency on the alert config and cookie.
    $renderer = \Drupal::service('renderer');
    $renderer->addCacheableDependency($build, $config);

    return $build;
  }

}
