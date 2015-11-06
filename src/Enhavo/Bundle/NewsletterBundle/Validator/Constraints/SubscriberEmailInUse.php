<?php
namespace Enhavo\Bundle\NewsletterBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SubscriberEmailInUse extends Constraint
{
    public $messageEmailInUse = 'The email is already in use.';
    public $messageMailAgain = 'We sent email again.';

    public function validatedBy()
    {
        return 'email_in_use';
    }
}