<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 24.05.16
 * Time: 15:27
 */

namespace Enhavo\Bundle\SearchBundle\Search\Filter;


interface SearchFilterInterface
{
    public function isGranted($resource);
}