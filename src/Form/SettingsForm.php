<?php

namespace Drupal\aspambot\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Locale\CountryManager;

/**
 * Class SettingsForm.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['aspambot.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $banned_countries = $this->config('aspambot.settings')->get('countries');
    $reverse = $this->config('aspambot.settings')->get('reverse');
    $countries = CountryManager::getStandardList();
    $form['#attached']['library'][] = 'aspambot/aspambot';
    $form['cnt_countries'] = [
      '#type' => 'details',
      '#title' => t('Countries Filter'),
      '#open' => TRUE,
    ];
    $form['cnt_countries']['countries'] = [
      '#type' => 'select',
      '#title' => 'Selected countries',
      '#multiple' => TRUE,
      '#empty_value' => 'none',
      '#empty_option' => 'None',
      '#options' => $countries,
      '#default_value' => $banned_countries,

    ];
    $form['cnt_countries']['reverse'] = [
      '#type' => 'checkbox',
      '#title' => 'Use as whitelist',
      '#default_value' => $reverse,
      '#description' => t('If selected, the list will be used as a whitelist rather than a blacklist.'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('aspambot.settings');
    $values = $form_state->getValues();
    $countries = $values['countries'] ?? [];
    $reverse = $values['reverse'] ?? 0;
    $config->set('countries', $countries)->save();
    $config->set('reverse', $reverse)->save();
  }

}
