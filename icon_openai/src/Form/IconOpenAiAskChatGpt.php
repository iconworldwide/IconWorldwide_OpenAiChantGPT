<?php

namespace Drupal\icon_openai\Form;

use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\icon_openai\IconOpenAiProcessor;
use Drupal\Core\Ajax\AjaxResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
class IconOpenAiAskChatGpt extends FormBase
{

  /**
   * @var IconOpenAiProcessor
   */
  public IconOpenAiProcessor $processor;

  /**
   * @param IconOpenAiProcessor $processor
   */
  public function __construct(IconOpenAiProcessor $processor)
  {
    $this->processor = $processor;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('icon_openai.chatgpt_processor'),
    );
  }


  public function getFormId()
  {
    return 'icon_openai_key_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['massage'] = [
      '#type' => 'markup',
      '#markup' => '<div class="result_message"></div>',
    ];

    $form['question'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Ask ChatGPT.'),
      '#placeholder' => $this->t('Question'),
    ];

    $form['actions'] = [
      '#type' => 'button',
      '#value' => $this->t('Ask a question'),
      '#ajax' => [
        'callback' => '::setMessage',
      ]
    ];

    $form['#attached']['library'][] = 'icon_openai/icon.chatgpt';

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {

  }

  public function setMessage(array &$form, FormStateInterface $form_state): AjaxResponse
  {
    $response = new AjaxResponse();

    // Get OpenAi Key from configurations.
    $openAiKey = $this->config('icon_openai.settings_form')->get('openai_key');

    if (empty($openAiKey)) {
      $getAnswer = '<div style="color: red" class="icon-chatgpt-error-message">' . $this->t('Answer: Missing API Key, please contact an administrator.') . '</div>';
    } else {
      // Get answer from ChatGPT.
      $answerText = $this->processor->getOpenAiAnswer($form_state->getValue('question'), $openAiKey);

      $getAnswer = '<div id="answerText">' . $answerText . '</div>
<textarea id="answeredText" style="opacity:0; position:absolute; pointer-events: none">' . strip_tags($answerText) . '</textarea>
<button id="copyOpenAiAnswer" class="button copyOpenAiAnswer" data-clipboard-action="copy" data-clipboard-target="#answeredText">Copy Answer</button>';
    }

    $dialog_options = [
      'dialogClass' => 'views-ui-dialog js-views-ui-dialog icon-chatgpt-modal-container',
      'width' => '60%',
    ];

    return $response->addCommand(new OpenModalDialogCommand( 'Answer', $getAnswer, $dialog_options));
  }
}

