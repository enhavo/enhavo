<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 28.06.18
 * Time: 19:03
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SplObjectExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('spl_object_hash', array($this, 'getSplObjectHash')),
        );
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('spl_object_hash', array($this, 'getSplObjectHash')),
        );
    }

    public function getSplObjectHash($object)
    {
        return spl_object_hash($object);
    }
}
