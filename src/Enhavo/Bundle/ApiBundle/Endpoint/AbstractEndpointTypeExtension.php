<?php

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Documentation\Writer;
use Enhavo\Component\Type\AbstractTypeExtension;

abstract class AbstractEndpointTypeExtension extends AbstractTypeExtension implements EndpointTypeExtensionInterface
{
    public function describe($options, Writer $writer)
    {

    }
}
