<?php

include (__DIR__ . '/vendor/autoload.php');

use GuzzleHttp\Client;
use Service\ApiClient;
use Service\ConfigProvider;
use Service\SessionManager;

$configProvider = new ConfigProvider(__DIR__ .'/config.json');

$apiclient = new ApiClient(new Client());
$sessionManager = new SessionManager();

if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    if ($_REQUEST['hub_verify_token'] === $configProvider->getParameter('verify_token')) {
        echo $challenge; die();
    }
}

$input = json_decode(file_get_contents('php://input'), true);

if ($input === null) {
    exit;
}

$decoded = $apiclient->getQuestion();

$message = $input['entry'][0]['messaging'][0]['message']['text'];
$question = html_entity_decode($decoded['results'][0]['question']);
$correctAnswer = html_entity_decode($decoded['results'][0]['correct_answer']);
$sender = $input['entry'][0]['messaging'][0]['sender']['id'];

$fb = new \Facebook\Facebook([
    'app_id' => $configProvider->getParameter('app_id'),
    'app_secret' => $configProvider->getParameter('app_secret')
]);

if (($message == "Start") || ($message == "start"))
{
    $data = [
        'messaging_type' => 'RESPONSE',
        'recipient' => [
            'id' => $sender,
        ],
        'message' => [
            'text' => "Question: " . $question . "\n\nChoose an answer: ",
        ]
    ];

    $sessionManager->setSessionState($question);

    $answer = $input['entry'][0]['messaging'][0]['message']['text'];
    $sessionManager->setSessionState($answer);
    if ($answer == $correctAnswer)
    {
        $data = [
            'messaging_type' => 'RESPONSE',
            'recipient' => [
                'id' => $sender,
            ],
            'message' => [
                'text' => $answer . " is a correct answer.",
            ]
        ];
    }
}

$response = $fb->post('/me/messages', $data, $configProvider->getParameter('access_token'));
