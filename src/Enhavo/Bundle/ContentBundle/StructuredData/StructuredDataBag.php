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


class StructuredDataBag
{
    private array $data = [];

    public function createType(string $type, $root = true): StructuredData
    {
        $typeData = new StructuredData();
        $typeData->setType($type);
        $typeData->setRoot($root);

        if (!array_key_exists($type, $this->data)) {
            $this->data[$type] = [];
        }

        $this->data[$type][] = $typeData;

        return $typeData;
    }

    public function getType(string $type): StructuredData
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
            /** @var StructuredData $typeData */
            foreach ($dataSet as $typeData) {
                if ($typeData->isRoot() && $typeData->count() > 0) {
                    $returnData[] = $typeData->normalize();
                }
            }
        }

        return $returnData;
    }
}
