<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\StructuredData;

use Enhavo\Bundle\ApiBundle\Data\Data;

class StructuredDataBag
{
    private array $data = [];

    public function createType(string $type): Data
    {
        $typeData = new Data();

        if (!array_key_exists($type, $this->data)) {
            $this->data[$type] = [];
        }

        $this->data[$type][] = $typeData;

        return $typeData;
    }

    public function getType(string $type): Data
    {
        if (!isset($this->data[$type])) {
            throw new \InvalidArgumentException(sprintf('The type "%s" does not exist.', $type));
        }

        return $this->data[$type][count($this->data[$type]) - 1];
    }

    public function hasType(string $type): bool
    {
        return isset($this->data[$type]);
    }

    public function toArray(): array
    {
        $returnData = [];

        foreach ($this->data as $typeName => $dataSet) {
            /** @var Data $typeData */
            foreach ($dataSet as $typeData) {
                $returnData[] = array_merge([
                    "@context" => "https://schema.org",
                ], $typeData->normalize());
            }
        }

        return $returnData;
    }
}
