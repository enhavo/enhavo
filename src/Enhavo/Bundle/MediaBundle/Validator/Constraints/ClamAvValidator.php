<?php

namespace Enhavo\Bundle\MediaBundle\Validator\Constraints;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\File\File as FileObject;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 *
 */
class ClamAvValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ClamAv) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ClamAv');
        }

        $path = $value instanceof FileObject ? $value->getPathname() : (string) $value;
        $command = sprintf('%s %s', $constraint->clamAvPath, $path);
        $process = new Process(explode(' ', $command));
        $exitCode = $process->run();

        if ($exitCode !== ClamAv::RESULT_OK) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{reason}}', $constraint->getResultText($exitCode))
                ->addViolation();
        }
    }
}
