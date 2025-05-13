<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
