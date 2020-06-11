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
    public function getAllClasses();

    public function load();

    public function getNormalizedData();
}
