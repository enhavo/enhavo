<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Tests\AutoGenerator\Generator;

use Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator\PrefixGenerator;

class PrefixGeneratorExtendTest extends PrefixGenerator
{
    /**
     * @var object
     */
    private $resource;

    protected function createPrefix(array $properties, $resource, array $options): string
    {
        $this->resource = $resource;

        return parent::createPrefix($properties, $resource, $options);
    }

    public function getResource(): object
    {
        return $this->resource;
    }
}
