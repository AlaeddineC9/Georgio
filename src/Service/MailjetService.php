<?php

namespace App\Service;

use Mailjet\Client;
use Mailjet\Resources;

class MailjetService
{
    private $mailjet;

    public function __construct(string $apiKey, string $apiSecret)
    {
        $this->mailjet = new Client($apiKey, $apiSecret, true, ['version' => 'v3.1']);
    }

    public function sendEmail($toEmail, $toName, $subject, $htmlContent)
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => 'restaurant@aubergegeorgio.fr',
                        'Name' => 'Auberge Georgio'
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'Subject' => $subject,
                    'HTMLPart' => $htmlContent
                ]
            ]
        ];

        $response = $this->mailjet->post(Resources::$Email, ['body' => $body]);

        if (!$response->success()) {
            throw new \Exception('Erreur lors de l\'envoi de l\'email : ' . $response->getReasonPhrase());
        }

        return $response;
    }
}
