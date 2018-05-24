<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 12:51
 */

namespace Enhavo\Bundle\AppBundle\Metadata;


interface MetadataFactoryInterface
{
    public function create($className, array $configuration = []);
}