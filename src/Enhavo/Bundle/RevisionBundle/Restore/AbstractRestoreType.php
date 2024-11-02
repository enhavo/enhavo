<?php

namespace Enhavo\Bundle\RevisionBundle\Restore;

use Enhavo\Bundle\ResourceBundle\Duplicate\Value;
use Enhavo\Component\Type\AbstractType;

/**
 * @property RestoreTypeInterface $parent
 */
abstract class AbstractRestoreType extends AbstractType implements RestoreTypeInterface
{
    public function restore($options, Value $subjectValue, Value $revisionValue, $context): void
    {

    }

    public function finish($options, Value $subjectValue, Value $revisionValue, $context): void
    {

    }
}
