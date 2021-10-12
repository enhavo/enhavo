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
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Http\RememberMe\RememberMeServicesInterface;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserManager
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var MailerManager */
    protected $mailerManager;

    /** @var RepositoryInterface */
    private $userRepository;

    /** @var UserMapperInterface */
    private $userMapper;

    /** @var TokenGeneratorInterface */
    private $tokenGenerator;

    /** @var TranslatorInterface */
    private $translator;

    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var RouterInterface */
    protected $router;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var RequestStack */
    private $requestStack;

    /** @var SessionAuthenticationStrategyInterface */
    private $sessionStrategy;

    /** @var UserCheckerInterface */
    private $userChecker;

    /** @var RememberMeServicesInterface|null */
    private $rememberMeService;

    /** @var array */
    private $defaultFirewall;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param MailerManager $mailerManager
     * @param RepositoryInterface $userRepository
     * @param UserMapperInterface $userMapper
     * @param TokenGeneratorInterface $tokenGenerator
     * @param TranslatorInterface $translator
     * @param FormFactoryInterface $formFactory
     * @param EncoderFactoryInterface $encoderFactory
     * @param RouterInterface $router
     * @param EventDispatcherInterface $eventDispatcher
     * @param TokenStorageInterface $tokenStorage
     * @param RequestStack $requestStack
     * @param SessionAuthenticationStrategyInterface $sessionStrategy
     * @param UserCheckerInterface $userChecker
     * @param RememberMeServicesInterface|null $rememberMeService
     * @param string $defaultFirewall
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        MailerManager $mailerManager,
        RepositoryInterface $userRepository,
        UserMapperInterface $userMapper,
        TokenGeneratorInterface $tokenGenerator,
        TranslatorInterface $translator,
        EncoderFactoryInterface $encoderFactory,
        RouterInterface $router,
        EventDispatcherInterface $eventDispatcher,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack,
        SessionAuthenticationStrategyInterface $sessionStrategy,
        UserCheckerInterface $userChecker,
        ?RememberMeServicesInterface $rememberMeService,
        string $defaultFirewall
    ) {
        $this->em = $entityManager;
        $this->mailerManager = $mailerManager;
        $this->userRepository = $userRepository;
        $this->userMapper = $userMapper;
        $this->tokenGenerator = $tokenGenerator;
        $this->translator = $translator;
        $this->encoderFactory = $encoderFactory;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->sessionStrategy = $sessionStrategy;
        $this->userChecker = $userChecker;
        $this->rememberMeService = $rememberMeService;
        $this->defaultFirewall = $defaultFirewall;
    }

    public function add(UserInterface $user)
    {
        $this->em->persist($user);
        $this->update($user);
        $event = new UserEvent(UserEvent::TYPE_CREATED, $user);
        $this->eventDispatcher->dispatch($event);
    }

    public function update(UserInterface $user, $flush = true)
    {
        $this->updateUsername($user);
        $this->updatePassword($user);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function updatePassword(UserInterface $user)
    {
        $this->hashPassword($user);
    }

    public function updateUsername(UserInterface $user)
    {
        $this->userMapper->setUsername($user);
    }

    private function enable(UserInterface $user)
    {
        $user->setEnabled(true);
        $user->setConfirmationToken(null);
    }

    public function verify(UserInterface $user, $flush = true)
    {
        $user->setVerified(true);
        $user->setConfirmationToken(null);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function changePassword(UserInterface $user)
    {
        $user->setConfirmationToken(null);
        $this->update($user);
        $event = new UserEvent(UserEvent::TYPE_PASSWORD_CHANGED, $user);
        $this->eventDispatcher->dispatch($event);
    }

    public function mapValues(UserInterface $user, array $values)
    {
        $this->userMapper->mapValues($user, $values);
    }

    public function login(UserInterface $user, ?Response $response = null, ?string $firewallName = null)
    {
        $this->userChecker->checkPreAuth($user);

        $token = new UsernamePasswordToken($user, null, $firewallName ?? $this->defaultFirewall, $user->getRoles());
        $request = $this->requestStack->getCurrentRequest();
        if (null !== $request) {
            $this->sessionStrategy->onAuthentication($request, $token);
            if (null !== $response && null !== $this->rememberMeService) {
                $this->rememberMeService->loginSuccess($request, $response, $token);
            }
        }
        $this->tokenStorage->setToken($token);
    }

    public function logout()
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null !== $request) {
            $request->getSession()->invalidate();
        }
        $this->tokenStorage->setToken(null);
    }

    private function hashPassword(UserInterface $user)
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

    public function register(UserInterface $user, RegistrationRegisterConfiguration $configuration)
    {
        $user->setConfirmationToken($this->tokenGenerator->generateToken());

        $user->setEnabled($configuration->isAutoEnabled());
        $user->setVerified($configuration->isAutoVerified());

        $this->add($user);

        if ($configuration->isMailEnabled()) {
            $this->sendRegistrationConfirmMail($user, $configuration);
        }
    }

    private function sendRegistrationConfirmMail(UserInterface $user, RegistrationRegisterConfiguration $configuration)
    {
        $message = $this->createUserMessage($user, $configuration);
        $message->setContext([
            'user' => $user,
            'confirmation_url' => $this->router->generate($configuration->getConfirmationRoute(), [
                'token' => $user->getConfirmationToken()
            ], RouterInterface::ABSOLUTE_URL),
            'configuration' => $configuration
        ]);
        $this->mailerManager->sendMessage($message);
    }

    public function activate(UserInterface $user)
    {
        $this->enable($user);
        $this->update($user);
        $event = new UserEvent(UserEvent::TYPE_ACTIVATED, $user);
        $this->eventDispatcher->dispatch($event);
    }

    public function confirm(UserInterface $user, RegistrationConfirmConfiguration $configuration)
    {
        $this->verify($user, false);
        $this->enable($user);
        $this->update($user);
        $event = new UserEvent(UserEvent::TYPE_REGISTRATION_CONFIRMED, $user);
        $this->eventDispatcher->dispatch($event);

        if ($configuration->isMailEnabled()) {
            $this->sendConfirmationMail($user, $configuration);
        }
    }

    private function sendConfirmationMail(UserInterface $user, RegistrationConfirmConfiguration $configuration)
    {
        $message = $this->createUserMessage($user, $configuration);
        $message->setContext([
            'user' => $user,
            'configuration' => $configuration
        ]);
        $this->mailerManager->sendMessage($message);
    }

    public function resetPassword(UserInterface $user, ResetPasswordRequestConfiguration $configuration)
    {
        $user->setConfirmationToken($this->tokenGenerator->generateToken());
        $this->update($user);
        $event = new UserEvent(UserEvent::TYPE_PASSWORD_RESET_REQUESTED, $user);
        $this->eventDispatcher->dispatch($event);

        if ($configuration->isMailEnabled()) {
            $this->sendResetPasswordMail($user, $configuration);
        }
    }

    private function sendResetPasswordMail(UserInterface $user, ResetPasswordRequestConfiguration $configuration)
    {
        $message = $this->createUserMessage($user, $configuration);
        $message->setContext([
            'user' => $user,
            'confirmation_url' => $this->router->generate($configuration->getConfirmationRoute(), [
                'token' => $user->getConfirmationToken()
            ], RouterInterface::ABSOLUTE_URL)
        ]);
        $this->mailerManager->sendMessage($message);
    }

    public function requestChangeEmail(UserInterface $user, ChangeEmailRequestConfiguration $configuration)
    {
        $user->setConfirmationToken($this->tokenGenerator->generateToken());
        $this->update($user);

        if ($configuration->isMailEnabled()) {
            $this->sendChangeEmailRequest($user, $configuration);
        }
    }

    public function sendChangeEmailRequest(UserInterface $user, ChangeEmailRequestConfiguration $configuration)
    {
        $message = $this->createUserMessage($user, $configuration);
        $message->setContext([
            'user' => $user,
            'confirmation_url' => $this->router->generate($configuration->getConfirmationRoute(), [
                'token' => $user->getConfirmationToken()
            ], RouterInterface::ABSOLUTE_URL),
            'configuration' => $configuration
        ]);
        $this->mailerManager->sendMessage($message);
    }

    public function changeEmail(UserInterface $user, $newEmail, ChangeEmailConfirmConfiguration $configuration)
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

    public function sendChangeEmailConfirmMail(UserInterface $user, ChangeEmailConfirmConfiguration $configuration)
    {
        $message = $this->createUserMessage($user, $configuration);
        $message->setContext([
            'user' => $user,
            'confirmation_url' => $this->router->generate($configuration->getConfirmationRoute(), [
                'token' => $user->getConfirmationToken()
            ], RouterInterface::ABSOLUTE_URL),
            'configuration' => $configuration
        ]);
        $this->mailerManager->sendMessage($message);
    }

    public function delete(UserInterface $user, DeleteConfirmConfiguration $configuration)
    {
        $this->em->remove($user);
        $this->em->flush();

        if ($configuration->isMailEnabled()) {
            $this->sendDeleteMail($user, $configuration);
        }
    }

    private function sendDeleteMail(UserInterface $user, DeleteConfirmConfiguration $configuration)
    {
        $message = $this->createUserMessage($user, $configuration);
        $message->setContext([
            'user' => $user,
            'configuration' => $configuration
        ]);
        $this->mailerManager->sendMessage($message);
    }

    public function requestVerification(UserInterface $user, VerificationRequestConfiguration $configuration)
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

    private function sendVerificationMail(UserInterface $user, VerificationRequestConfiguration $configuration)
    {
        $message = $this->createUserMessage($user, $configuration);
        $message->setContext([
            'user' => $user,
            'confirmation_url' => $this->router->generate($configuration->getConfirmationRoute(), [
                'token' => $user->getConfirmationToken()
            ], RouterInterface::ABSOLUTE_URL),
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
