<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends ResourceController
{
    public function changePasswordAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'change_password',
            'request_configuration' => $configuration,
        ]);

        return $view->getResponse($request);
    }
}
