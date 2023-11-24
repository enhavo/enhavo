<?php

namespace Enhavo\Bundle\TaxonomyBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class TermUnique extends Constraint
{
    public $message = 'term.validation.term_unique.message';
    public $translationDomain = 'EnhavoTaxonomyBundle';

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
