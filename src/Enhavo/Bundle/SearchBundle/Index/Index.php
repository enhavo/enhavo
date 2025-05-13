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

use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property $type    IndexTypeInterface
 * @property $parents IndexTypeInterface[]
 */
class Index extends AbstractContainerType
{
    /** @return IndexData[] */
    public function getIndexData($model): array
    {
        $indexDataBuilder = new IndexDataBuilder();

        foreach ($this->parents as $parent) {
            $parent->buildIndex($this->options, $model, $indexDataBuilder);
        }

        $this->type->buildIndex($this->options, $model, $indexDataBuilder);

        return $indexDataBuilder->getIndex();
    }

    /** @return string[] */
    public function getRawData($model): array
    {
        $indexDataBuilder = new IndexDataBuilder();

        foreach ($this->parents as $parent) {
            $parent->buildRawData($this->options, $model, $indexDataBuilder);
        }

        $this->type->buildRawData($this->options, $model, $indexDataBuilder);

        return $indexDataBuilder->getRawData();
    }
}
