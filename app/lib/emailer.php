<?php
require 'vendor/autoload.php';

use \Mailjet\Resources;

class Emailer
{
  var $config;
  var $client;

  public function __construct(&$config)
  {
    $this->config = $config;
    $this->client = new \Mailjet\Client($this->config['mailjet_apikey_public'], $this->config['mailjet_apikey_private'],true, ['version' => 'v3.1']);
  }

  public function send($recipientEmail, $recipientName, $subject, $content)
  {
    $body = [
      'Messages' => [
        [
          'From' => [
            'Email' => $this->config['email_address_sender'],
            'Name' => $this->config['support_team']
          ],
          'To' => [
            [
              'Email' => $recipientEmail,
              'Name' => $recipientName
            ]
          ],
          'ReplyTo' => [
            'Email' => $this->config['email_address_replyto'],
            'Name' => $this->config['support_team']
          ],
          'Subject' => $subject,
          'TextPart' => $content
        ]
      ]
    ];

    if ($this->config['email_simulation']) {
      return true;
    }

    $response = $this->client->post(Resources::$Email, ['body' => $body]);

    return $response->success();
  }
}
