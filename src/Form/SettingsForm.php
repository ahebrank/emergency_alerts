<?php

namespace Drupal\emergency_alerts\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

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
      '#title' => t('Override every page with alert'),
      '#description' => t('Only the main alert page is displayed (html--emergency-alert.html.twig template). WARNING: this will replace every page on your site with a (single static) template!'),
      '#default_value' => $config->get('override'),
      '#states' => [
        'visible' => [
          ':input[name="show_block"]' => ['checked' => FALSE],
        ]
      ]
    ];

    $form['show_block'] = [
      '#type'  => 'checkbox',
      '#title' => t('Show alert message'),
      '#description' => t('Use emergency_alert block (emergency-alert.html.twig) to show alert content'),
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
      '#description' => t('How serious is this alert?  Affects style of block-level alert. NOTE: it is recommended you do NOT use this alerting system for generic announcements. Reserve for emergencies only.'),
      '#options' => [
        'announcement' => t('Announcement'),
        'warning' => t('Warning'),
        'danger' => t('Danger'),
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
      '#title' => t('Alert title'),
      '#description' => t('One or two word title for your emergency notification'),
      '#default_value' => $config->get('alert_title'),
    ];

    $message = $config->get('alert_message', NULL);
    $form['alert_message'] = [
      '#type'  => 'text_format',
      '#title' => t('Alert message. More details about the emergency -- include relevant links and contact information.'),
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

    parent::submitForm($form, $form_state);
  }

}
