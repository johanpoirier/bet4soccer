<?php
require 'vendor/autoload.php';

class Emailer
{
  var $config;
  var $client;

  public function __construct(&$config)
  {
    $this->config = $config;
    $sendInBlueConfig = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->config['sendinblue_apikey']);
    $this->client = new SendinBlue\Client\Api\SMTPApi(new GuzzleHttp\Client(), $sendInBlueConfig);
  }

  public function send($recipientEmail, $recipientName, $subject, $content)
  {
    if ($this->config['email_simulation']) {
      return true;
    }

    if ($this->config(['email_use_third_party_sender']) === true) {
      return $this->sendWithSendinBlue($recipientEmail, $recipientName, $subject, $content);
    } else {
      return utf8_mail($recipientEmail, $subject, $content, $this->config['support_team'], $this->config['email_address_sender']);
    }
  }

  public function sendWithSendinBlue($recipientEmail, $recipientName, $subject, $content)
  {
    $email = new SendinBlue\Client\Model\SendSmtpEmail([
      'sender' => new SendinBlue\Client\Model\SendSmtpEmailSender([
        'email' => $this->config['email_address_sender'],
        'name' => $this->config['support_team']
      ]),
      'to' => $recipientEmail,
      'replyTo' => new SendinBlue\Client\Model\SendSmtpEmailReplyTo([
        'email' => $this->config['email_address_replyto'],
        'name' => $this->config['support_team']
      ]),
      'subject' => $subject,
      'htmlContent' => $content
    ]);
    
    try {
      $result = $this->client->sendTransacEmail($email);
      return true;
    } catch (Exception $e) {
      echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
      return false;
    }
  }
}
