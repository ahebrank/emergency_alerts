<?php

namespace Drupal\emergency_alerts\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;

/**
 * Class SettingsForm.
 *
 * @package Drupal\emergency_alerts\Form
 */
class SettingsForm extends ConfigFormBase {

  private $packageName = 'emergency_alerts';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return $this->packageName . '_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [$this->packageName . '.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config($this->packageName . '.settings');

    $form['override'] = [
      '#type'  => 'checkbox',
      '#title' => $this->t('Override every page with alert'),
      '#description' => $this->t('Only the main alert page is displayed (html--emergency-alert.html.twig template). WARNING: this will replace every page on your site with a (single static) template!'),
      '#default_value' => $config->get('override'),
      '#states' => [
        'visible' => [
          ':input[name="show_block"]' => ['checked' => FALSE],
        ]
      ]
    ];

    $form['show_block'] = [
      '#type'  => 'checkbox',
      '#title' => $this->t('Show alert message'),
      '#description' => $this->t('Use emergency_alert block (emergency-alert.html.twig) to show alert content'),
      '#default_value' => $config->get('show_block'),
      '#states' => [
        'visible' => [
          ':input[name="override"]' => ['checked' => FALSE],
        ]
      ]
    ];

    $form['alert_level'] = [
      '#type'  => 'select',
      '#title' => t('Alert level'),
      '#description' => $this->t('How serious is this alert?  Affects style of block-level alert. NOTE: it is recommended you do NOT use this alerting system for generic announcements. Reserve for emergencies only.'),
      '#options' => [
        'announcement' => $this->t('Announcement'),
        'warning' => $this->t('Warning'),
        'danger' => $this->t('Danger'),
      ],
      '#default_value' => $config->get('alert_level'),
      '#states' => [
        'visible' => [
          ':input[name="override"]' => ['checked' => FALSE],
        ]
      ]
    ];

    $form['alert_title'] = [
      '#type'  => 'textfield',
      '#title' => $this->t('Alert title'),
      '#description' => $this->t('One or two word title for your emergency notification'),
      '#default_value' => $config->get('alert_title'),
    ];

    $message = $config->get('alert_message', NULL);
    $form['alert_message'] = [
      '#type'  => 'text_format',
      '#title' => $this->t('Alert message. More details about the emergency -- include relevant links and contact information.'),
      '#description' => '',
      '#format' => $message ? $message['format'] : 'basic_html',
      '#default_value' => $message ? $message['value'] : '',
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config($this->packageName . '.settings');
    $form_state->cleanValues();

    foreach ($form_state->getValues() as $key => $value) {
      $config->set($key, $value);
    }
    $config->save();

    // You'd think this would clear automatically?
    Cache::invalidateTags(['config:' . $this->packageName . '.settings']);

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    // Add a cache dependency on the module config.
    return Cache::mergeTags(parent::getCacheTags(), ['config:' . $this->configKey]);
  }

}
