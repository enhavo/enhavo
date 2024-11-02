<?php

namespace Enhavo\Bundle\RevisionBundle\Restore\Type;

use Enhavo\Bundle\ResourceBundle\Duplicate\Value;
use Enhavo\Bundle\RevisionBundle\Restore\AbstractRestoreType;

class PropertyRestoreType extends AbstractRestoreType
{
    public function restore($options, Value $subjectValue, Value $revisionValue, $context): void
    {
        $value = $revisionValue->getValue();
        if (!is_null($value) && !is_scalar($value)) {
            throw new \InvalidArgumentException(sprintf('Restore type property only accept scalar values but "%s" given', gettype($value)));
        }

        $subjectValue->setValue($value);
    }

    public static function getName(): ?string
    {
        return 'property';
    }
}
