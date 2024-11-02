<?php

namespace Enhavo\Bundle\RevisionBundle\Restore;

use Enhavo\Bundle\ResourceBundle\Duplicate\Value;
use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface RestoreTypeInterface extends TypeInterface
{
    public function restore($options, Value $subjectValue, Value $revisionValue, $context): void;

    public function finish($options, Value $subjectValue, Value $revisionValue, $context): void;

    public function configureOptions(OptionsResolver $resolver);
}
