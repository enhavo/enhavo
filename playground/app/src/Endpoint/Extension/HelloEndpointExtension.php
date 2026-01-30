<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Endpoint\Extension;

use App\Endpoint\HelloEndpoint;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointTypeExtension;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Symfony\Component\HttpFoundation\Request;

class HelloEndpointExtension extends AbstractEndpointTypeExtension
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $data->set('extension', 'Here is a extension');
    }

    public static function getExtendedTypes(): array
    {
        return [HelloEndpoint::class];
    }
}
