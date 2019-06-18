<?php
namespace Enhavo\Bundle\BlockBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VideoUrlValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $parsedUrl = parse_url($value);
        if(array_key_exists('host', $parsedUrl)) {
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