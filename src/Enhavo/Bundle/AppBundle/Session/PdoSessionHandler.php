<?php

namespace Enhavo\Bundle\AppBundle\Session;

use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler as BasePdoSessionHandler;

class PdoSessionHandler extends BasePdoSessionHandler
{
    public function __construct($pdoOrDsn = null, array $options = array())
    {
        parent::__construct($pdoOrDsn, $options);

        if(array_key_exists('gc_maxlifetime', $options)) {
            ini_set('session.gc_maxlifetime', $options['gc_maxlifetime']);
        }
    }
}