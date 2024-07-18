<?php

namespace Enhavo\Bundle\ResourceBundle\Input;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface InputMergeInterface
{
    public static function mergeOptions($before, $current): array;
}
