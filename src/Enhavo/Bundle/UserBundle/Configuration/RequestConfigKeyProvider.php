<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Configuration;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestConfigKeyProvider implements ConfigKeyProviderInterface
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    public function getConfigKey(): ?string
    {
        $request = $this->requestStack->getMainRequest();

        return $request->attributes->get('_config');
    }
}
