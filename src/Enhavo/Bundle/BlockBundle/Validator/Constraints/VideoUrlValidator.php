<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VideoUrlValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $parsedUrl = parse_url($value);
        if (array_key_exists('host', $parsedUrl)) {
            if (!preg_match('[youtube.]', $parsedUrl['host']) && !preg_match('[vimeo.]', $parsedUrl['host'])) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        } else {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
