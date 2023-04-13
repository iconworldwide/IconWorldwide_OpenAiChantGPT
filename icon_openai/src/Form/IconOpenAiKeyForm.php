<?php

namespace Drupal\icon_openai\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * {@inheritdoc}
 */
class IconOpenAiKeyForm extends ConfigFormBase {

  public function getFormId()
  {
    return 'icon_openai_key_form';
  }

    protected function getEditableConfigNames() {
    return ['icon_openai.settings_form'];
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['openai_key'] = [
      '#type' => 'textfield',
      '#title' => t('Enter your OpenAI key:'),
      '#default_value' => $this->config('icon_openai.settings_form')->get('openai_key') ?? '',
      '#required' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $this->config('icon_openai.settings_form')
      ->set('openai_key', $form_state->getValue('openai_key'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}
