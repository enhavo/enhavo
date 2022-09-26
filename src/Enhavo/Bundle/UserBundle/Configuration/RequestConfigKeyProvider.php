<?php

namespace Enhavo\Bundle\UserBundle\Configuration;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestConfigKeyProvider implements ConfigKeyProviderInterface
{
    public function __construct(
        private RequestStack $requestStack
    )
    {
    }

    public function getConfigKey(): ?string
    {
        $request = $this->requestStack->getMainRequest();
        return $request->attributes->get('_config');
    }
}
