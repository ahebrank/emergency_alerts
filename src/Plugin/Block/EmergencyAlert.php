<?php

namespace Drupal\emergency_alerts\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an 'EmergencyAlert' Block
 *
 * @Block(
 *   id = "emergency_alert",
 *   admin_label = @Translation("Emergency Alert"),
 * )
 */
class EmergencyAlert extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
  	$config = \Drupal::config('emergency_alerts.settings');

  	// get config variables
  	$message = $config->get('alert_message');
  	$level = $config->get('alert_level');

  	// TODO: move the closable rules to config
  	// check for a cookie
  	$cookies = \Drupal::request()->cookies;
  	$cookie_set = FALSE;
  	if ($cookies->has('announcement')) {
  		$cookie_set = ($cookies->get('announcement') == 'closed');
  	}

  	// should alert be shown?
  	$show_alert = $config->get('show_block') 
  		&& ($level != 'announcement' || !$cookie_set);

    $render = [
      '#cache' => [
      	'tags' => [
      		'config:emergency_alerts.settings',
      	],
      ],
      '#theme' => 'emergency_alert',
      '#alert_on' => $show_alert,
      '#title' => $config->get('alert_title'),
      '#message' => [
      	'#type' => 'markup',
      	'#markup' => $message['value'],
      ],
      '#alert_level' => $level,
    ];

    return $render;
  }
}