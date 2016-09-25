<?php
/**
 * AuthenticationSuccessHandler.php
 *
 * @since 21/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\Security\Authentication;

use Enhavo\Bundle\UserBundle\Event\UserEvents;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    use ContainerAwareTrait;

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $response = parent::onAuthenticationSuccess($request, $token);
        $this->dispatch($token);
        return $response;
    }

    public function getEventDispatcher()
    {
        return $this->container->get('event_dispatcher');
    }

    private function dispatch(TokenInterface $token)
    {
        $user = $token->getUser();
        $this->getEventDispatcher()->dispatch(UserEvents::LOGIN, new GenericEvent($user));
    }
}