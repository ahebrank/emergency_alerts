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

    $form['control'] = [
      '#type' => 'fieldset',
      '#title' => t('Emergency Alerts Settings'),
    ];

    $form['control']['override'] = [
      '#type'  => 'checkbox',
      '#title' => t('Override every page with alert'),
      '#description' => t('Only the main alert page is displayed (html--emergency template)'),
      '#default_value' => $config->get('override'),
    ];

    $form['control']['show_block'] = [
      '#type'  => 'checkbox',
      '#title' => t('Show alert message'),
      '#description' => t('Use emergency_alert block to show alert content'),
      '#default_value' => $config->get('show_block'),
    ];

    $form['control']['alert_level'] = [
      '#type'  => 'select',
      '#title' => t('Alert level'),
      '#description' => t('How serious is this alert?  Affects style of block-level alert'),
      '#options' => [
        'announcement' => t('Announcement'),
        'warning' => t('Warning'),
        'danger' => t('Danger'),
      ],
      '#default_value' => $config->get('alert_level'),
    ];

    $form['control']['alert_title'] = [
      '#type'  => 'textfield',
      '#title' => t('Alert title'),
      '#description' => t('One or two word title'),
      '#default_value' => $config->get('alert_title'),
    ];

    $message = $config->get('alert_message', null);
    $form['control']['alert_message'] = [
      '#type'  => 'text_format',
      '#title' => t('Alert message'),
      '#description' => '',
      '#format' => $message? $message['format']:'basic_html',
      '#default_value' => $message? $message['value']:'',
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
