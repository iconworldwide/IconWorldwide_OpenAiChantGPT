<?php

namespace Drupal\icon_openai;

class IconOpenAiProcessor
{
  public function getOpenAiAnswer($question, $openAiKey): string
  {
    $apiKey = $openAiKey;
    $model = "text-davinci-003";
    $prompt = $question;
    $maxTokens = 256;

    $data = [
      'model' => $model,
      'prompt' => $prompt,
      'max_tokens' => $maxTokens
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Authorization: Bearer " . $apiKey]);

    $response = curl_exec($ch);
    $jsonResponse = json_decode($response, true);
    curl_close($ch);

    if (!empty($jsonResponse["error"])) {
      $responseChatGpt = $jsonResponse["error"]["message"];
    } else {
      $responseChatGpt = nl2br(htmlentities($jsonResponse["choices"][0]["text"]));
      $responseChatGpt = preg_replace('/^(?:<br\s*\/?>\s*)+/', '', $responseChatGpt);
    }

    return $responseChatGpt;
  }
}
