<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-20
 * Time: 11:06
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

    /**
     * @return object
     */
    public function getResource(): object
    {
        return $this->resource;
    }
}
