<?php

namespace Enhavo\Bundle\UserBundle\Form\EventListener;

use Enhavo\Bundle\UserBundle\Model\Credentials;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class CredentialsEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SessionInterface $session,
        private CsrfTokenManager $tokenManager,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }

    public function onPreSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $dataClass = $form->getConfig()->getDataClass();

        /** @var Credentials $credentials */
        $credentials = $event->getData();
        if ($credentials === null) {
            $credentials = new $dataClass;
        }

        $credentials->setCsrfToken($this->getCsrfToken());
        $credentials->setUserIdentifier($this->getUserIdentifier());
        $credentials->setRememberMe($this->isRememberMe());

        $event->setData($credentials);
    }

    private function getUserIdentifier(): string
    {
        /** @var Credentials $credentials */
        $credentials = $this->session->get('_security.credentials', null);
        return null === $credentials ? '' : $credentials->getUserIdentifier();
    }

    private function isRememberMe(): bool
    {
        /** @var Credentials $credentials */
        $credentials = $this->session->get('_security.credentials', null);
        return null === $credentials && $credentials->isRememberMe();
    }

    private function getCsrfToken(): string
    {
        return $this->tokenManager->getToken('authenticate')->getValue();
    }
}
