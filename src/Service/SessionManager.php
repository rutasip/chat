<?php

namespace Service;

class SessionManager 
{
    public function getSessionState() 
    {
        return json_decode(file_get_contents(__DIR__ . '/session_storage.json'), true);
    }

    public function setSessionState($state) 
    {
        file_put_contents(json_encode($state));
    }
}