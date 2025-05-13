<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Index\Metadata;

use Enhavo\Component\Metadata\Extension\Config;

class Metadata extends \Enhavo\Component\Metadata\Metadata
{
    /** @var Config[] array */
    private array $index = [];
    /** @var Config[] array */
    private array $filter = [];

    public function getIndex(): array
    {
        return $this->index;
    }

    public function setIndex(array $index): void
    {
        $this->index = $index;
    }

    public function getFilter(): array
    {
        return $this->filter;
    }

    public function setFilter(array $filter): void
    {
        $this->filter = $filter;
    }
}
