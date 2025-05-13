<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SplObjectExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('spl_object_hash', [$this, 'getSplObjectHash']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('spl_object_hash', [$this, 'getSplObjectHash']),
        ];
    }

    public function getSplObjectHash($object)
    {
        return spl_object_hash($object);
    }
}
