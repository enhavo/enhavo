<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Component\Type\TypeExtensionInterface;
use Symfony\Component\HttpFoundation\Request;

interface EndpointTypeExtensionInterface extends TypeExtensionInterface
{
    public function handleRequest($options, Request $request, Data $data, Context $context);

    public function describe($options, Path $path);
}
