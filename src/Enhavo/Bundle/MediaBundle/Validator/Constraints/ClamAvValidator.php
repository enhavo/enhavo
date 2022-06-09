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
    const RESULT_OK = 0;
    const RESULT_VIRUS_FOUND = 1;
    const RESULT_SOME_ERROR = 2;

    const RESULT_CODES = [
        self::RESULT_OK => 'No virus found',
        self::RESULT_VIRUS_FOUND => 'Virus(es) found',
        self::RESULT_SOME_ERROR => 'Some error(s) occured',
    ];

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ClamAv) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ClamAv');
        }

        $path = $value instanceof FileObject ? $value->getPathname() : (string) $value;
        $command = sprintf('%s %s', $constraint->clamAvPath, $path);
        $process = new Process(explode(' ', $command));
        $exitCode = $process->run();

        if ($exitCode > self::RESULT_SOME_ERROR) {
            throw new ProcessFailedException($process);
        }

        if ($exitCode !== self::RESULT_OK) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{reason}}', self::RESULT_CODES[$exitCode])
                ->addViolation();
        }
    }
}
