<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-10
 * Time: 13:40
 */

namespace Enhavo\Component\Metadata;


interface DriverInterface
{
    /** Get all FQCN as array */
    public function getAllClasses(): array;

    /** Get normalized data of a class or null, and if no data exists false */
    public function loadClass($className): array|null|false;
}
