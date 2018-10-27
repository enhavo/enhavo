<?php

namespace Enhavo\Bundle\AppBundle\Filter;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface FilterInterface extends TypeInterface
{
    public function render($options, $name);

    public function buildQuery(FilterQuery $query, $options, $value);

    public function getPermission($options);

    public function configureOptions(OptionsResolver $optionsResolver);
}