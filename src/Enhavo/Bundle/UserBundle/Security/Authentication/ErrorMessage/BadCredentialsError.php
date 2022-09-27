<?php

namespace Enhavo\Bundle\UserBundle\Security\Authentication\ErrorMessage;

use Enhavo\Bundle\UserBundle\Security\Authentication\ErrorMessageInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Contracts\Translation\TranslatorInterface;

class BadCredentialsError implements ErrorMessageInterface
{
    public function __construct(
        private TranslatorInterface $translator,
    )
    {
    }

    public function supports($error): bool
    {
        return $error instanceof BadCredentialsException;
    }

    public function getMessage(): string
    {
        return $this->translator->trans('login.error.credentials', [], 'EnhavoUserBundle');
    }
}
