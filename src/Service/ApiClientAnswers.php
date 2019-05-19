<?php

namespace Service;

class ApiClientAnswers
{
    private $client;

    public function __construct($client) 
    {
        $this->client = $client;
    }

    public function getAnswers() 
    {
        $response = $this->client->get('https://opentdb.com/api.php?difficulty=easy&amount=1');
        return json_decode($response->getBody()->getContents(), true);
    }    
}

