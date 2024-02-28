<?php

namespace Enhavo\Bundle\AppBundle\Area;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestAreaResolver implements AreaResolverInterface
{
    public function __construct(
        private readonly RequestStack $requestStack
    )
    {
    }

    public function resolve(): ?string
    {
        $request = $this->requestStack->getMainRequest();
        return $request->attributes->get('_area');
    }
}
