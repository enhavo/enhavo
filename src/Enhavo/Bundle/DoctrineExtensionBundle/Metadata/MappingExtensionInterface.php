<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-09
 * Time: 21:22
 */

namespace Enhavo\Component\DoctrineExtension\Mapping;

use Doctrine\Persistence\Mapping\Driver\MappingDriver;

interface MappingExtensionInterface
{
    public function loadDriver(MappingDriver $driver);

    public function loadClass($className, MappingDriver $driver);
}
