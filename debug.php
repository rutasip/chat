<?php

use GuzzleHttp\Client;
use Service\ConfigProvider;
use Service\ApiClient;

include (__DIR__ . '/vendor/autoload.php');

$client = new ApiClient(new Client());

var_dump($client->getQuestion());