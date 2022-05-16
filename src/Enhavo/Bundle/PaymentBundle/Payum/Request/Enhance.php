<?php

namespace Enhavo\Bundle\PaymentBundle\Payum\Request;

use Payum\Core\Request\Generic;

class Enhance extends Generic
{
    public function getDetails()
    {
        return $this->getModel();
    }

    public function setDetails($details)
    {
        $this->setDetails(array_merge($details, $this->getDetails()));
    }
}
