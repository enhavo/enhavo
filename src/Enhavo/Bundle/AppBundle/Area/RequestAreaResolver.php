<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Area;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestAreaResolver implements AreaResolverInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function resolve(): ?string
    {
        $request = $this->requestStack->getMainRequest();

        return $request->attributes->get('_area');
    }
}
