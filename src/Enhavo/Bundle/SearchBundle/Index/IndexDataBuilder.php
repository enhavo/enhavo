<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Index;

class IndexDataBuilder
{
    /** @var IndexData[] */
    private array $indexData = [];

    private array $rawData = [];

    public function addIndex(IndexData $indexData)
    {
        $this->indexData[] = $indexData;
    }

    public function removeIndex(IndexData $indexData)
    {
        if (false !== $key = array_search($indexData, $this->indexData, true)) {
            array_splice($this->indexData, $key, 1);
        }
    }

    public function getIndex(): array
    {
        return $this->indexData;
    }

    public function addRawData(string $rawData)
    {
        $this->rawData[] = $rawData;
    }

    public function getRawData(): array
    {
        return $this->rawData;
    }
}
