<?php

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Component\Type\AbstractTypeExtension;

abstract class AbstractEndpointTypeExtension extends AbstractTypeExtension implements EndpointTypeExtensionInterface
{
    public function describe($options, Path $path)
    {

    }
}
