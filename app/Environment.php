<?php

use Symfony\Component\Yaml\Yaml;

class Environment
{
    const PROD = 'prod';
    const DEV = 'dev';
    const TEST = 'test';


    private $environment;
    private $debug;

    public function __construct()
    {
        $this->setEnvironmentInformation();
    }

    protected function setEnvironmentInformation()
    {

        if(in_array(getenv('ENHAVO_DEBUG'), [self::PROD, self::DEV, self::TEST])) {
            $this->debug = getenv('ENHAVO_DEBUG');
        } else {
            $config = Yaml::parse($this->getParametersYMLFilePath());
            if (isset($config['parameters']['debug'])) {
                $this->debug = (bool)$config['parameters']['debug'];
            } else {
                $this->debug = true;
            }
        }

        if(in_array(getenv('ENHAVO_ENV'), [self::PROD, self::DEV, self::TEST])) {
            $this->environment = getenv('ENHAVO_ENV');
        } else {
            if(isset($config['parameters']['environment'])) {
                $this->environment = $config['parameters']['environment'];
            } else {
                $this->environment = 'dev';
            }
        }
    }

    protected function getParametersYMLFilePath()
    {
        $file = __DIR__.'/config/parameters.yml';
        if(!file_exists($file)) {
            throw new \RuntimeException('parameters.yml not exists, please add configurition to this file before go on');
        }
        return $file;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function isDebug()
    {
        return $this->debug;
    }
}