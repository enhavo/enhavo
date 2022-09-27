<?php

namespace Enhavo\Bundle\UserBundle\Security\Authentication\ErrorMessage;

use Enhavo\Bundle\UserBundle\Exception\NotEnabledException;
use Enhavo\Bundle\UserBundle\Security\Authentication\ErrorMessageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotEnabledError implements ErrorMessageInterface
{
    public function __construct(
        private TranslatorInterface $translator,
    )
    {
    }

    public function supports($error): bool
    {
        return $error instanceof NotEnabledException;
    }

    public function getMessage(): string
    {
        return $this->translator->trans('login.error.disabled', [], 'EnhavoUserBundle');
    }
}
