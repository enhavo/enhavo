<?php
/**
 * UserManager.php
 *
 * @since 23/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\User;

use FOS\UserBundle\Doctrine\UserManager as FOSUserManager;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccountStatusException;

class UserManager extends FOSUserManager
{
    use ContainerAwareTrait;

    public function sendResetEmail(UserInterface $user, $template = null, $route = null)
    {
        $tokenGenerator = $this->container->get('fos_user.util.token_generator');
        $user->setConfirmationToken($tokenGenerator->generateToken());

        $this->container->get('fos_user.mailer')->sendResettingEmailMessage($user, $template, $route);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->updateUser($user);
    }

    public function sendRegisterConfirmEmail(UserInterface $user, $template = null, $route = null)
    {
        $tokenGenerator = $this->container->get('fos_user.util.token_generator');
        $user->setConfirmationToken($tokenGenerator->generateToken());

        $this->container->get('fos_user.mailer')->sendRegisterConfirmMessage($user, $template, $route);

        $this->updateUser($user);
    }

    public function authenticateUser(UserInterface $user, Response $response)
    {
        try {
            $this->container->get('fos_user.security.login_manager')->logInUser(
                $this->container->getParameter('fos_user.firewall_name'),
                $user,
                $response);
        } catch (AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }
}