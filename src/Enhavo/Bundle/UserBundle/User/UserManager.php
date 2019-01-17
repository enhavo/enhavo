<?php
/**
 * UserManager.php
 *
 * @since 23/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\User;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Doctrine\UserManager as FOSUserManager;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use FOS\UserBundle\Security\LoginManagerInterface;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;
use FOS\UserBundle\Util\TokenGenerator;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccountStatusException;

class UserManager extends FOSUserManager
{
    use ContainerAwareTrait;

    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    /**
     * @var UserMailer
     */
    private $mailer;

    /**
     * @var LoginManagerInterface
     */
    private $loginManager;

    /**
     * @var string
     */
    private $firewall;

    /**
     * UserManager constructor.
     *
     * @param PasswordUpdaterInterface $passwordUpdater
     * @param CanonicalFieldsUpdater $canonicalFieldsUpdater
     * @param ObjectManager $om
     * @param $class
     * @param TokenGenerator $tokenGenerator
     * @param UserMailer $mailer
     * @param LoginManagerInterface $loginManager
     * @param $firewall
     */
    public function __construct(
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdater $canonicalFieldsUpdater,
        ObjectManager $om,
        $class,
        TokenGenerator $tokenGenerator,
        UserMailer $mailer,
        LoginManagerInterface $loginManager,
        $firewall
    ) {
        parent::__construct($passwordUpdater, $canonicalFieldsUpdater, $om, $class);
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
        $this->loginManager = $loginManager;
        $this->firewall = $firewall;
    }

    public function sendResetEmail(UserInterface $user, $template = null, $route = null)
    {
        $user->setConfirmationToken($this->tokenGenerator->generateToken());
        $this->mailer->sendResettingEmailMessage($user, $template, $route);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->updateUser($user);
    }

    public function sendRegisterConfirmEmail(UserInterface $user, $template = null, $route = null)
    {
        $user->setConfirmationToken($this->tokenGenerator->generateToken());
        $this->mailer->sendRegisterConfirmMessage($user, $template, $route);
        $this->updateUser($user);
    }

    public function authenticateUser(UserInterface $user, Response $response)
    {
        try {
            $this->loginManager->logInUser( $this->firewall, $user, $response);
        } catch (AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }
}