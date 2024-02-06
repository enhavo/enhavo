<?php

namespace Enhavo\Bundle\SearchBundle\Index\Type;

use Enhavo\Bundle\SearchBundle\Index\IndexDataBuilder;
use Enhavo\Bundle\SearchBundle\Index\IndexTypeInterface;
use Enhavo\Component\Type\AbstractType;

abstract class AbstractIndexType extends AbstractType implements IndexTypeInterface
{
    public function buildIndex(array $options, $model, IndexDataBuilder $builder): void
    {

    }

    public function buildRawData(array $options, $model, IndexDataBuilder $builder): void
    {
        $this->buildIndex($options, $model, $builder);

        foreach ($builder->getIndex() as $index) {
            $builder->addRawData($index->getValue());
        }
    }
}
