<?php

namespace Enhavo\Bundle\ResourceBundle\Input;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface InputInterface
{
    public function setOptions($options): void;

    public function configureOptions(OptionsResolver $resolver): void;
}
