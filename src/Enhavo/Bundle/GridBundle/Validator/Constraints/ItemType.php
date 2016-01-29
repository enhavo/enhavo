<?php
namespace Enhavo\Bundle\GridBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ItemType extends Constraint
{
    public $message = 'Please choose a youtube or vimeo video!';


    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return 'item_type';
    }
}