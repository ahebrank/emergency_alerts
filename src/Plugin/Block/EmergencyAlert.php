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

    // TODO: move the closable rules to config
    // check for a cookie.
    $cookies = \Drupal::request()->cookies;
    $cookie_set = FALSE;
    if ($cookies->has('announcement')) {
      $cookie_set = ($cookies->get('announcement') == 'closed');
    }

    // Should alert be shown?
    $show_alert = $config->get('show_block')
          && ($level != 'announcement' || !$cookie_set);

    if (!$show_alert) {
      return [];
    }

    $message_render = [];
    if ($message) {
      $message_render = check_markup($message['value'], $message['format']);
    }

    $build = [
      '#theme' => 'emergency_alert',
      '#title' => $config->get('alert_title'),
      '#message' => $message_render,
      '#alert_level' => $level,
      '#attached' => [
        'library' => [
          'emergency_alerts/persist_close'
        ],
      ],
    ];
    return $build;
  }

}
