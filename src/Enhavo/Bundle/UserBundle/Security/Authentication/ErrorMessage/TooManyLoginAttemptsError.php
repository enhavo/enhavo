<?php

namespace Enhavo\Bundle\UserBundle\Security\Authentication\ErrorMessage;

use Enhavo\Bundle\UserBundle\Exception\TooManyLoginAttemptsException;
use Enhavo\Bundle\UserBundle\Security\Authentication\ErrorMessageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TooManyLoginAttemptsError implements ErrorMessageInterface
{
    public function __construct(
        private TranslatorInterface $translator,
    )
    {
    }

    public function supports($error): bool
    {
        return $error instanceof TooManyLoginAttemptsException;
    }

    public function getMessage(): string
    {
        return $this->translator->trans('login.error.max_failed_attempts', [], 'EnhavoUserBundle');
    }
}
