<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Security\Authentication;

use Laminas\Stdlib\PriorityQueue;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class AuthenticationError
{
    private PriorityQueue $errorMessages;

    public function __construct(
        private AuthenticationUtils $authenticationUtils,
        private TranslatorInterface $translator,
    ) {
        $this->errorMessages = new PriorityQueue();
    }

    public function addErrorMessage(ErrorMessageInterface $errorMessage, int $priority = 10)
    {
        $this->errorMessages->insert($errorMessage, $priority);
    }

    public function getError(): ?string
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        if (null === $error) {
            return null;
        }

        /** @var ErrorMessageInterface $errorMessage */
        foreach ($this->errorMessages as $errorMessage) {
            if ($errorMessage->supports($error)) {
                return $errorMessage->getMessage();
            }
        }

        return $this->translator->trans('login.error.general', [], 'EnhavoUserBundle');
    }
}
