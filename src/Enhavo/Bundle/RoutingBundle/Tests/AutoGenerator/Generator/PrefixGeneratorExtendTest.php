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

    protected function existsPrefix($prefix, $resource, array $options): bool
    {
        $this->resource = $resource;

        return parent::existsPrefix($prefix, $resource, $options);
    }

    public function getResource(): object
    {
        return $this->resource;
    }
}
