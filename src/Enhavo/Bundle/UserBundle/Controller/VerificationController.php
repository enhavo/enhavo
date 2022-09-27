<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Enhavo\Bundle\UserBundle\Exception\TokenInvalidException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class VerificationController
 * @package Enhavo\Bundle\UserBundle\Controller
 */
class VerificationController extends AbstractUserController
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * ResetPasswordController constructor.
     * @param UserManager $userManager
     * @param ConfigurationProvider $provider
     * @param UserRepository $userRepository
     */
    public function __construct(UserManager $userManager, ConfigurationProvider $provider, UserRepository $userRepository)
    {
        parent::__construct($userManager, $provider);
        $this->userRepository = $userRepository;
    }

    public function requestAction(Request $request, $csrfToken)
    {
        $configuration = $this->provider->getVerificationRequestConfiguration();

        /** @var UserInterface $user */
        $user = $this->getUser();

        if ($user === null) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('user-verification', $csrfToken)) {
            if (!$user->isVerified()) {
                $this->userManager->requestVerification($user, $configuration);
            }
        } else {
            throw $this->createNotFoundException();
        }

        return $this->render($this->getTemplate($configuration->getTemplate()), [
            'user' => $user,
        ]);
    }

    public function confirmAction(Request $request, $token)
    {
        $configuration = $this->provider->getVerificationConfirmConfiguration();

        $user = $this->userRepository->findByConfirmationToken($token);

        if ($user === null) {
            throw new TokenInvalidException();
        }

        $this->userManager->verify($user);

        return $this->render($this->getTemplate($configuration->getTemplate()));
    }
}
