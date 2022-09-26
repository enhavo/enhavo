<?php

namespace Enhavo\Bundle\UserBundle\Security\Authentication;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class AuthenticationError
{
    public function __construct(
        private AuthenticationUtils $authenticationUtils,
        private TranslatorInterface $translator,
    )
    {
    }

    public function getError(): ?string
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        if ($error === null) {
            return null;
        }

        if ($error instanceof BadCredentialsException) {
            $this->translator->trans('login.error.credential', [], 'EnhavoUserBundle');
        }

        return $this->translator->trans('login.error.credential', [], 'EnhavoUserBundle');
    }
}
