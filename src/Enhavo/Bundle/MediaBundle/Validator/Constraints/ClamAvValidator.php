<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Validator\Constraints;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\File\File as FileObject;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ClamAvValidator extends ConstraintValidator
{
    public const RESULT_OK = 0;
    public const RESULT_VIRUS_FOUND = 1;
    public const RESULT_SOME_ERROR = 2;

    public const RESULT_CODES = [
        self::RESULT_OK => 'No virus found',
        self::RESULT_VIRUS_FOUND => 'Virus(es) found',
        self::RESULT_SOME_ERROR => 'Some error(s) occurred',
    ];

    public function __construct(
        public array $config,
    ) {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ClamAv) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ClamAv');
        }

        $path = $value instanceof FileObject ? $value->getPathname() : (string) $value;
        $perm = fileperms($path) | 0644;
        chmod($path, $perm);
        $command = sprintf('%s %s', $constraint->clamscanPath ?? $this->config['clamscan_path'], $path);
        $process = new Process(explode(' ', $command));
        $exitCode = $process->run();

        if ($exitCode > self::RESULT_SOME_ERROR) {
            throw new ProcessFailedException($process);
        }

        if (self::RESULT_OK !== $exitCode) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{reason}}', self::RESULT_CODES[$exitCode])
                ->addViolation();
        }
    }
}
