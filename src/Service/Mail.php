<?php
namespace App\Service;

use Mailjet\Client;
use Mailjet\Resources;

class Mail 
{
    private $api_key;
    private $api_key_secret;

    public function __construct()
    {
        $this->api_key = $_ENV['MAILJET_API_KEY'] ?? '7195b503dbe748c207b8fb6d4ff969a4';
        $this->api_key_secret = $_ENV['MAILJET_API_SECRET'] ?? '996bd8fc1d193a449c4308e2f6746089';
    }

    public function send($toEmail, $toName, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body = 
        [
            'Messages' => 
            [
                [
                    'From' => 
                    [
                        'Email' => "malektabbabi77@gmail.com",
                        'Name' => "GearX"
                    ],
                    'To' => 
                    [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'TemplateID' => 3732103,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => ['content' => $content]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
    }
}