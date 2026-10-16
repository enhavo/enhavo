<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Behat\Controller;

use Doctrine\Persistence\ObjectRepository;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Logs in a user without credentials. Only for use in test environments!
 */
class SecurityController
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly ObjectRepository $userRepository,
    ) {
    }

    public function loginAction(Request $request): Response
    {
        $username = $request->query->get('username');

        $user = $this->userRepository->findOneBy(['userIdentifier' => $username]);
        if (!$user instanceof UserInterface) {
            throw new NotFoundHttpException(sprintf('User "%s" not found', $username));
        }

        $this->userManager->login($user);

        return new Response('Ok');
    }
}
