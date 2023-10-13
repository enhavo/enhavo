<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

class AbstractUserController extends AbstractController
{
    use TemplateResolverTrait;

    public function __construct(
        protected UserManager $userManager,
        protected ConfigurationProvider $provider,
    )
    {
    }

    protected function getFlashMessages()
    {
        $flashBag = $this->container->get('request_stack')->getSession()->getFlashBag();
        $messages = [];
        $types = ['success', 'error', 'notice', 'warning'];
        foreach ($types as $type) {
            foreach ($flashBag->get($type) as $message) {
                $messages[] = [
                    'message' => is_array($message) ? $message['message'] : $message,
                    'type' => $type
                ];
            }
        }
        return $messages;
    }
}
