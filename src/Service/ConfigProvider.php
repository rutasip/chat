<?php

namespace Service;

use Exception;

class ConfigProvider 
{
    private $configFile;

    public function __construct($configFile) 
    {
        $this->configFile = $configFile;
    }

    public function getParameter($parameter)
    {
        $config = $this->parseConfig();
        if (!isset($config[$parameter])) {
            throw new Exception(sprintf('Config parameter %s does not exist', $parameter));
        }
        return $config[$parameter];
    }

    private function parseConfig()
    {
        return json_decode(file_get_contents($this->configFile), true);
    }
}