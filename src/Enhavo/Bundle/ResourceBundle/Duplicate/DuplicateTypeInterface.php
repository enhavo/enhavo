<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate;

use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface DuplicateTypeInterface extends TypeInterface
{
    public function duplicate($options, Value $newValue, Value $originalValue, $context): void;

    public function finish($options, Value $newValue, Value $originalValue, $context): void;

    public function configureOptions(OptionsResolver $resolver);
}
