<?php
/**
 * @author blutze-media
 * @since 2020-10-28
 */

namespace Enhavo\Bundle\UserBundle\Security;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Model\UserInterface as EnhavoUserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function __construct(
        private UserManager $userManager,
        private ConfigurationProvider $configurationProvider,
        private RequestStack $requestStack,
    )
    {}

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof EnhavoUserInterface) {
           return;
        }

        $configKey = $this->getConfigKey();
        $configuration = $this->configurationProvider->getLoginConfiguration($configKey);
        $this->userManager->checkPreAuth($user, $configuration);
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof EnhavoUserInterface) {
            return;
        }

        if (false === $user->isEnabled()) {
            throw new BadCredentialsException('User account not activated');
        }
    }

    private function getConfigKey()
    {
        return $this->requestStack->getCurrentRequest()->attributes->get('_config');
    }

}
