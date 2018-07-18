<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 28.06.18
 * Time: 19:03
 */

namespace Enhavo\Bundle\AppBundle\Twig;

class SplObject extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('spl_object_hash', array($this, 'getSplObjectHash')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('spl_object_hash', array($this, 'getSplObjectHash')),
        );
    }

    public function getSplObjectHash($object)
    {
        return spl_object_hash($object);
    }

    public function getName()
    {
        return 'spl_object';
    }
}