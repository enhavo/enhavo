<?php

use Symfony\Component\Dotenv\Dotenv;

class Environment
{
    const PROD = 'prod';
    const DEV = 'dev';
    const TEST = 'test';

    /**
     * @var string
     */
    private $environment;

    /**
     * @var bool
     */
    private $debug;

    public function __construct()
    {
        $dotEnv = new Dotenv();
        $dotEnv->load(__DIR__.'/../.env');

        if(getenv('DEBUG')) {
            $this->debug = getenv('DEBUG');
        } else {
            $this->debug = true;
        }

        if(in_array(getenv('ENVIRONMENT'), [self::PROD, self::DEV, self::TEST])) {
            $this->environment = getenv('ENVIRONMENT');
        } else {
            $this->environment = self::DEV;
        }
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