<?php

namespace Enhavo\Bundle\AppBundle\Filter;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface FilterTypeInterface extends TypeInterface
{
    public function createViewData($options, $name);

    public function buildQuery(FilterQuery $query, $options, $value);

    public function getPermission($options);

    public function isHidden($options);

    public function configureOptions(OptionsResolver $optionsResolver);
}