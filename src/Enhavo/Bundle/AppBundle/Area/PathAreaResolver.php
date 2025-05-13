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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PathAreaResolver implements AreaResolverInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly array $config,
    ) {
    }

    public function resolve(): ?string
    {
        $request = $this->requestStack->getMainRequest();

        foreach ($this->config as $key => $config) {
            if (!isset($config['path'])) {
                continue;
            }

            $paths = is_array($config['path']) ? $config['path'] : [$config['path']];
            foreach ($paths as $path) {
                if ($this->matches($path, $request)) {
                    return $key;
                }
            }
        }

        return null;
    }

    private function matches($path, Request $request): bool
    {
        return preg_match('{'.$path.'}', rawurldecode($request->getPathInfo()));
    }
}
