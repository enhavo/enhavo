<?php

/**
 * LoginWidget.php
 *
 * @since 23/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\ThemeBundle\Widget\WidgetInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class LoginWidget extends AbstractType implements WidgetInterface
{
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'login';
    }

    public function render($options)
    {
        $template = 'EnhavoUserBundle:Theme:Widget/login.html.twig';
        if(isset($options['template'])) {
            $template = $options['template'];
        }

        if(isset($options['csrf_token'])) {
            $token = $options['csrf_token'];
        } else {
            $token = $this->getCsrfToken();
        }

        if(isset($options['last_username'])) {
            $lastUsername = $options['last_username'];
        } else {
            $lastUsername = $this->getLastUserName();
        }

        if(isset($options['error'])) {
            $error = $options['error'];
        } else {
            $error = $this->getError();
        }

        return $this->renderTemplate($template, [
            'csrf_token' => $token,
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    private function getCsrfToken()
    {
        return $this->container->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
    }

    private function getError()
    {
        $request = $this->container->get('request_stack')->getMasterRequest();
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
        $session = $this->container->get('session');
        $lastUsernameKey = Security::LAST_USERNAME;
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);
        return $lastUsername;
    }
}