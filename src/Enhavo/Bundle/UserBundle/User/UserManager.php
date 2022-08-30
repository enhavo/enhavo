<?php
/**
 * @author blutze-media
 * @since 2020-10-22
 */

namespace Enhavo\Bundle\UserBundle\User;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Mailer\MailerManager;
use Enhavo\Bundle\AppBundle\Mailer\Message;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\UserBundle\Configuration\ChangeEmail\ChangeEmailConfirmConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\ChangeEmail\ChangeEmailRequestConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Delete\DeleteConfirmConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\MailConfigurationInterface;
use Enhavo\Bundle\UserBundle\Configuration\Registration\RegistrationConfirmConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Registration\RegistrationRegisterConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\ResetPassword\ResetPasswordRequestConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Verification\VerificationRequestConfiguration;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Mapper\UserMapperInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserManager
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected MailerManager                        $mailerManager,
        private UserMapperInterface                    $userMapper,
        private TokenGeneratorInterface                $tokenGenerator,
        private TranslatorInterface                    $translator,
        private EncoderFactoryInterface                $encoderFactory,
        protected RouterInterface                      $router,
        private EventDispatcherInterface               $eventDispatcher,
        private TokenStorageInterface                  $tokenStorage,
        private RequestStack                           $requestStack,
        private SessionAuthenticationStrategyInterface $sessionStrategy,
        private UserCheckerInterface                   $userChecker,
        private string                                 $defaultFirewall,
    ) {

    }

    public function add(UserInterface $user): void
    {
        $this->em->persist($user);
        $this->update($user);
        $event = new UserEvent(UserEvent::TYPE_CREATED, $user);
        $this->eventDispatcher->dispatch($event);
    }

    public function update(UserInterface $user, $flush = true): void
    {
        $this->updateUsername($user);
        $this->updatePassword($user);

        if ($flush) {
            $this->em->flush();
        }
    }

    private function updatePassword(UserInterface $user): void
    {
        $this->hashPassword($user);
    }

    public function updateLoggedIn(UserInterface $user, $flush = true): void
    {
        $user->setLastLogin(new \DateTime());

        if ($flush) {
            $this->em->flush();
        }
    }

    private function updateUsername(UserInterface $user): void
    {
        $this->userMapper->setUsername($user);
    }

    private function enable(UserInterface $user): void
    {
        $user->setEnabled(true);
        $user->setConfirmationToken(null);
    }

    public function verify(UserInterface $user, $flush = true): void
    {
        $user->setVerified(true);
        $user->setConfirmationToken(null);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function changePassword(UserInterface $user): void
    {
        $user->setLastFailedLoginAttempt(null);
        $user->setFailedLoginAttempts(0);
        $user->setPasswordUpdatedAt(new \DateTime());
        $user->setConfirmationToken(null);
        $this->update($user);
        $event = new UserEvent(UserEvent::TYPE_PASSWORD_CHANGED, $user);
        $this->eventDispatcher->dispatch($event);
    }

    public function mapValues(UserInterface $user, array $values): void
    {
        $this->userMapper->mapValues($user, $values);
    }

    public function login(UserInterface $user, ?string $firewallName = null): void
    {
        $this->userChecker->checkPreAuth($user);

        $this->updateLoggedIn($user);

        $token = new UsernamePasswordToken($user, null, $firewallName ?? $this->defaultFirewall, $user->getRoles());
        $request = $this->requestStack->getCurrentRequest();
        if (null !== $request) {
            $this->sessionStrategy->onAuthentication($request, $token);
        }
        $this->tokenStorage->setToken($token);
    }

    public function logout(): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $request?->getSession()->invalidate();
        $this->tokenStorage->setToken(null);
    }

    /**
     * @throws Exception
     */
    private function hashPassword(UserInterface $user): void
    {
        $plainPassword = $user->getPlainPassword();
        if (0 === strlen($plainPassword)) {
            return;
        }
        $encoder = $this->encoderFactory->getEncoder($user);
        if ($encoder instanceof NativePasswordEncoder) {
            $user->setSalt(null);
        } else {
            $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
            $user->setSalt($salt);
        }
        $hashedPassword = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }

    public function register(UserInterface $user, RegistrationRegisterConfiguration $configuration): void
    {
        $user->setConfirmationToken($this->tokenGenerator->generateToken());

        $user->setEnabled($configuration->isAutoEnabled());
        $user->setVerified($configuration->isAutoVerified());

        $this->add($user);

        if ($configuration->isMailEnabled()) {
            $this->sendRegistrationConfirmMail($user, $configuration);
        }
    }

    private function sendRegistrationConfirmMail(UserInterface $user, RegistrationRegisterConfiguration $configuration): void
    {
        $message = $this->createUserMessage($user, $configuration);
        $message->setContext([
            'user' => $user,
            'confirmation_url' => $this->router->generate($configuration->getConfirmationRoute(), [
                'token' => $user->getConfirmationToken()
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'configuration' => $configuration
        ]);
        $this->mailerManager->sendMessage($message);
    }

    public function activate(UserInterface $user): void
    {
        $this->enable($user);
        $this->update($user);
        $event = new UserEvent(UserEvent::TYPE_ACTIVATED, $user);
        $this->eventDispatcher->dispatch($event);
    }

    public function confirm(UserInterface $user, RegistrationConfirmConfiguration $configuration): void
    {
        $this->verify($user, false);
        if ($configuration->isAutoEnabled()) {
            $this->enable($user);
        }

        $this->update($user);
        $event = new UserEvent(UserEvent::TYPE_REGISTRATION_CONFIRMED, $user);
        $this->eventDispatcher->dispatch($event);

        if ($configuration->isMailEnabled()) {
            $this->sendConfirmationMail($user, $configuration);
        }
    }

    private function sendConfirmationMail(UserInterface $user, RegistrationConfirmConfiguration $configuration): void
    {
        $message = $this->createUserMessage($user, $configuration);
        $message->setContext([
            'user' => $user,
            'configuration' => $configuration
        ]);
        $this->mailerManager->sendMessage($message);
    }

    /**
     * @throws Exception
     */
    public function resetPassword(UserInterface $user, ResetPasswordRequestConfiguration $configuration): void
    {
        $user->setConfirmationToken($this->tokenGenerator->generateToken());
        $this->update($user);
        $event = new UserEvent(UserEvent::TYPE_PASSWORD_RESET_REQUESTED, $user);
        $this->eventDispatcher->dispatch($event);

        if ($configuration->isMailEnabled()) {
            $this->sendResetPasswordMail($user, $configuration);
        }
    }

    private function sendResetPasswordMail(UserInterface $user, ResetPasswordRequestConfiguration $configuration): void
    {
        $message = $this->createUserMessage($user, $configuration);
        $message->setContext([
            'user' => $user,
            'confirmation_url' => $this->router->generate($configuration->getConfirmationRoute(), [
                'token' => $user->getConfirmationToken()
            ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
        $this->mailerManager->sendMessage($message);
    }

    public function requestChangeEmail(UserInterface $user, ChangeEmailRequestConfiguration $configuration): void
    {
        $user->setConfirmationToken($this->tokenGenerator->generateToken());
        $this->update($user);

        if ($configuration->isMailEnabled()) {
            $this->sendChangeEmailRequest($user, $configuration);
        }
    }

    public function sendChangeEmailRequest(UserInterface $user, ChangeEmailRequestConfiguration $configuration): void
    {
        $message = $this->createUserMessage($user, $configuration);
        $message->setContext([
            'user' => $user,
            'confirmation_url' => $this->router->generate($configuration->getConfirmationRoute(), [
                'token' => $user->getConfirmationToken()
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'configuration' => $configuration
        ]);
        $this->mailerManager->sendMessage($message);
    }

    public function changeEmail(UserInterface $user, $newEmail, ChangeEmailConfirmConfiguration $configuration): void
    {
        $user->setEmail($newEmail);
        $user->setVerified(false);
        $user->setConfirmationToken($this->tokenGenerator->generateToken());
        $this->update($user);
        $event = new UserEvent(UserEvent::TYPE_EMAIl_CHANGED, $user);
        $this->eventDispatcher->dispatch($event);

        if ($configuration->isMailEnabled()) {
            $this->sendChangeEmailConfirmMail($user, $configuration);
        }
    }

    public function sendChangeEmailConfirmMail(UserInterface $user, ChangeEmailConfirmConfiguration $configuration): void
    {
        $message = $this->createUserMessage($user, $configuration);
        $message->setContext([
            'user' => $user,
            'confirmation_url' => $this->router->generate($configuration->getConfirmationRoute(), [
                'token' => $user->getConfirmationToken()
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'configuration' => $configuration
        ]);
        $this->mailerManager->sendMessage($message);
    }

    public function delete(UserInterface $user, DeleteConfirmConfiguration $configuration): void
    {
        $this->em->remove($user);
        $this->em->flush();

        if ($configuration->isMailEnabled()) {
            $this->sendDeleteMail($user, $configuration);
        }
    }

    private function sendDeleteMail(UserInterface $user, DeleteConfirmConfiguration $configuration): void
    {
        $message = $this->createUserMessage($user, $configuration);
        $message->setContext([
            'user' => $user,
            'configuration' => $configuration
        ]);
        $this->mailerManager->sendMessage($message);
    }

    public function requestVerification(UserInterface $user, VerificationRequestConfiguration $configuration): void
    {
        if ($user->isVerified()) {
            return;
        }

        $user->setConfirmationToken($this->tokenGenerator->generateToken());
        $this->update($user);

        if ($configuration->isMailEnabled()) {
            $this->sendVerificationMail($user, $configuration);
        }
    }

    private function sendVerificationMail(UserInterface $user, VerificationRequestConfiguration $configuration): void
    {
        $message = $this->createUserMessage($user, $configuration);
        $message->setContext([
            'user' => $user,
            'confirmation_url' => $this->router->generate($configuration->getConfirmationRoute(), [
                'token' => $user->getConfirmationToken()
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'configuration' => $configuration
        ]);
        $this->mailerManager->sendMessage($message);
    }

    private function createUserMessage(UserInterface $user, MailConfigurationInterface $configuration): Message
    {
        $message = $this->mailerManager->createMessage();
        $message->setSubject($this->translator->trans($configuration->getMailSubject(), [], $configuration->getTranslationDomain()));
        $message->setTemplate($configuration->getMailTemplate());
        $message->setTo($user->getEmail());
        $message->setFrom($configuration->getMailFrom() ?? $this->mailerManager->getDefaults()->getMailFrom());
        $message->setSenderName($this->translator->trans($configuration->getMailSenderName() ?? $this->mailerManager->getDefaults()->getMailSenderName(), [], $configuration->getTranslationDomain()));
        $message->setContentType($configuration->getMailContentType() ?? Message::CONTENT_TYPE_PLAIN);

        return $message;
    }
}
