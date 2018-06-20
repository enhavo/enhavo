<?php

namespace Enhavo\Bundle\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class HierarchyNotCircular extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Hierarchy circle detected';

    public $parentProperty = 'parent';

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
