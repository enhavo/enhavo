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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class AuthenticationError
{
    private PriorityQueue $errorMessages;

    private ?Request $request = null;
    private ?AuthenticationException $error = null;

    public function __construct(
        private AuthenticationUtils $authenticationUtils,
        private TranslatorInterface $translator,
        private RequestStack $requestStack,
    ) {
        $this->errorMessages = new PriorityQueue();
    }

    public function addErrorMessage(ErrorMessageInterface $errorMessage, int $priority = 10)
    {
        $this->errorMessages->insert($errorMessage, $priority);
    }

    public function getError(): ?string
    {
        $error = $this->getLastAuthenticationError();
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

    private function getLastAuthenticationError(): ?AuthenticationException
    {
        // reading the error removes it from the session, so keep it for further reads in the same request
        $request = $this->requestStack->getCurrentRequest();
        if (null !== $this->error && $request === $this->request) {
            return $this->error;
        }

        $this->request = $request;
        $this->error = $this->authenticationUtils->getLastAuthenticationError();

        return $this->error;
    }
}
