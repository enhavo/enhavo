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
    /**
     * @var string
     */
    public $message = 'enhavo_newsletter.subscriber.exists';

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'subscriber_exists';
    }
}