<?php

namespace Enhavo\Bundle\UserBundle\Component;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('user_login', template: 'theme/component/user/login.html.twig')]
class LoginComponent
{
    #[ExposeInTemplate(name: 'csrf_token')]
    public ?string $csrfToken = null;

    #[ExposeInTemplate(name: 'last_username')]
    public ?string $lastUsername = null;

    #[ExposeInTemplate]
    public ?string $error = null;

    #[ExposeInTemplate(name: 'failure_path')]
    public ?string $failurePath = null;

    public function __construct(
        private RequestStack $requestStack,
        private CsrfTokenManagerInterface $tokenManager
    ) {
    }

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'config' => 'theme',
            'csrf_token' => null,
            'last_username' => null,
            'error' => null,
            'failure_path' => null,
        ]);
        return $this->createViewData($resolver->resolve($data));
    }

    private function createViewData(array $options)
    {
        if ($options['csrf_token']) {
            $token = $options['csrf_token'];
        } else {
            $token = $this->getCsrfToken();
        }

        if ($options['last_username']) {
            $lastUsername = $options['last_username'];
        } else {
            $lastUsername = $this->getLastUserName();
        }

        if ($options['error']) {
            $error = $options['error'];
        } else {
            $error = $this->getError();
        }

        return [
            'csrfToken' => $token,
            'lastUsername' => $lastUsername,
            'error' => $error,
            'failurePath' => $options['failure_path']
        ];
    }

    private function getCsrfToken()
    {
        return $this->tokenManager->getToken('authenticate')->getValue();
    }

    private function getError()
    {
        $request = $this->requestStack->getMainRequest();
        $session = $request->getSession();
        $authErrorKey = Security::AUTHENTICATION_ERROR;

        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null;
        }
        return $error;
    }

    private function getLastUserName()
    {
        $request = $this->requestStack->getMainRequest();
        $session = $request->getSession();
        return (null === $session) ? '' : $session->get(Security::LAST_USERNAME);
    }
}
