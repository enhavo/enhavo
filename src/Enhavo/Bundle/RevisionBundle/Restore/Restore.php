<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-03-28
 * Time: 15:31
 */

namespace Enhavo\Bundle\RevisionBundle\Restore;

use Enhavo\Bundle\ResourceBundle\Duplicate\Value;
use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property RestoreTypeInterface $type
 * @property RestoreTypeInterface[] $parents
 */
class Restore extends AbstractContainerType
{
    function restore($subject, $revision, array $context = []): mixed
    {
        $subjectValue = new Value();
        $subjectValue->setValue($subject);

        $revisionValue = new Value();
        $revisionValue->setValue($revision);

        foreach ($this->parents as $parent) {
            $parent->restore($this->options, $subjectValue, $revisionValue, $context);
        }

        $this->type->restore($this->options, $subjectValue, $revisionValue, $context);

        return $subjectValue->getValue();
    }
}
