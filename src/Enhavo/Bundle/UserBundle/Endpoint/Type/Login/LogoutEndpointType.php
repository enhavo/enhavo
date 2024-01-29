<?php

namespace Enhavo\Bundle\UserBundle\Endpoint\Type\Login;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Symfony\Component\HttpFoundation\Request;

class LogoutEndpointType extends AbstractEndpointType
{
    public static function getName(): ?string
    {
        return 'user_logout';
    }
    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        throw new \LogicException('You must activate the logout in your security firewall configuration.');
    }
}
