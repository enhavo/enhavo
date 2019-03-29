<?php

namespace Enhavo\Bundle\AppBundle\Column;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ColumnTypeInterface extends TypeInterface
{
    public function createColumnViewData(array $options);

    public function createResourceViewData(array $options, $resource);

    public function configureOptions(OptionsResolver $resolver);
}
