<?php

namespace Enhavo\Bundle\UserBundle\Security\Authentication\ErrorMessage;

use Enhavo\Bundle\UserBundle\Exception\VerificationRequiredException;
use Enhavo\Bundle\UserBundle\Security\Authentication\ErrorMessageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class VerificationRequiredError implements ErrorMessageInterface
{
    public function __construct(
        private TranslatorInterface $translator,
    )
    {
    }

    public function supports($error): bool
    {
        return $error instanceof VerificationRequiredException;
    }

    public function getMessage(): string
    {
        return $this->translator->trans('login.error.verification_required', [], 'EnhavoUserBundle');
    }
}
