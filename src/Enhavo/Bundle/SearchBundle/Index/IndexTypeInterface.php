<?php

namespace Enhavo\Bundle\SearchBundle\Index;

use Enhavo\Component\Type\TypeInterface;

interface IndexTypeInterface extends TypeInterface
{
    public function buildIndex(array $options, $model, IndexDataBuilder $builder): void;

    public function buildRawData(array $options, $model, IndexDataBuilder $builder): void;
}
