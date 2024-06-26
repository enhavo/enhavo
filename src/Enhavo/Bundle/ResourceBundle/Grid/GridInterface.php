<?php

namespace Enhavo\Bundle\ResourceBundle\Grid;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface GridInterface
{
    public function setOptions($options): void;

    public function configureOptions(OptionsResolver $resolver): void;
}
