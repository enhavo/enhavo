<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 24.05.16
 * Time: 15:30
 */

namespace Enhavo\Bundle\SearchBundle\Search\Filter;


class PublicFilter implements SearchFilterInterface
{
    public function isGranted($resource){

        //check if resource is public
        if (method_exists($resource['object'], 'isPublic')) {
            if ($resource['object']->isPublic()) {
                return true;
            }
        }
        return false;
    }
}