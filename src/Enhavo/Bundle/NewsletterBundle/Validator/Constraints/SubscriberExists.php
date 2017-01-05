<?php
/**
 * SubscriberExists.php
 *
 * @since 05/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class SubscriberExists extends Constraint
{
    public $message = 'The subscriber "%string%" already exists';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return 'subscriber_exists';
    }
}