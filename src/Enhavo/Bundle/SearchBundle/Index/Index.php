<?php

namespace Enhavo\Bundle\SearchBundle\Index;

use Enhavo\Component\Type\AbstractContainerType;

class Index extends AbstractContainerType
{
    /** @var IndexTypeInterface */
    protected $type;

    /** @var IndexTypeInterface[] */
    protected $parents;

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
