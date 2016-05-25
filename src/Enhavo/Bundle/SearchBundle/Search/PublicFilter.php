<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 24.05.16
 * Time: 15:30
 */

namespace Enhavo\Bundle\SearchBundle\Search;


class PublicFilter implements SearchFilterInterface
{
    public function isGranted($resource){

        //check if resource is public
        if (method_exists($resource['object'], 'getPublic')) {
            if ($resource['object']->getPublic()) {
                return true;
            }
        }
        return false;
    }
}