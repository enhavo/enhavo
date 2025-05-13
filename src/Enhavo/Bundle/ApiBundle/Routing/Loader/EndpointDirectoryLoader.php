<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Routing\Loader;

use Symfony\Component\Routing\Loader\AttributeDirectoryLoader;

class EndpointDirectoryLoader extends AttributeDirectoryLoader
{
    public function supports(mixed $resource, ?string $type = null): bool
    {
        if ('endpoint' === $type) {
            return true;
        }

        if ($type || !\is_string($resource)) {
            return false;
        }

        try {
            return is_dir($this->locator->locate($resource));
        } catch (\Exception $e) {
            return false;
        }
    }
}
