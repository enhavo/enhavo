<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Exception\ConfigurationException;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AbstractUserController extends AbstractController
{
    use TemplateTrait;

    /** @var UserManager */
    protected $userManager;

    /** @var ConfigurationProvider */
    protected $provider;

    /**
     * AbstractUserController constructor.
     * @param UserManager $userManager
     * @param ConfigurationProvider $provider
     */
    public function __construct(UserManager $userManager, ConfigurationProvider $provider)
    {
        $this->userManager = $userManager;
        $this->provider = $provider;
    }

    protected function getFlashMessages()
    {
        $flashBag = $this->container->get('session')->getFlashBag();
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

    protected function getConfigKey(Request $request)
    {
        $key = $request->attributes->get('_config', null);

        if (!is_string($key)) {
            throw ConfigurationException::configKeyNotFound($request);
        }

        return $key;
    }
}
