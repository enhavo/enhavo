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
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Mapper\UserMapperInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
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
    private $entityManager;

    /** @var MailerManager */
    private $mailerManager;

    /** @var RepositoryInterface */
    private $userRepository;

    /** @var UserMapperInterface */
    private $userMapper;

    /** @var TokenGeneratorInterface */
    private $tokenGenerator;

    /** @var TranslatorInterface */
    private $translator;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var RouterInterface */
    private $router;

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
    private $config;

    /** @var array */
    private $mail;

    /** @var array */
    private $parameters;

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
     * @param array $config
     * @param array $mail
     * @param array $parameters
     */
    public function __construct(EntityManagerInterface $entityManager, MailerManager $mailerManager, RepositoryInterface $userRepository, UserMapperInterface $userMapper, TokenGeneratorInterface $tokenGenerator, TranslatorInterface $translator, FormFactoryInterface $formFactory, EncoderFactoryInterface $encoderFactory, RouterInterface $router, EventDispatcherInterface $eventDispatcher, TokenStorageInterface $tokenStorage, RequestStack $requestStack, SessionAuthenticationStrategyInterface $sessionStrategy, UserCheckerInterface $userChecker, ?RememberMeServicesInterface $rememberMeService, array $config, array $mail, array $parameters)
    {
        $this->entityManager = $entityManager;
        $this->mailerManager = $mailerManager;
        $this->userRepository = $userRepository;
        $this->userMapper = $userMapper;
        $this->tokenGenerator = $tokenGenerator;
        $this->translator = $translator;
        $this->formFactory = $formFactory;
        $this->encoderFactory = $encoderFactory;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->sessionStrategy = $sessionStrategy;
        $this->userChecker = $userChecker;
        $this->rememberMeService = $rememberMeService;
        $this->config = $config;
        $this->mail = $mail;
        $this->parameters = $parameters;
    }

    public function login(UserInterface $user, ?Response $response = null, ?string $firewallName = null)
    {
        $this->userChecker->checkPreAuth($user);

        $token = $this->createToken($firewallName??$this->getDefaultFirewall(), $user);
        $request = $this->requestStack->getCurrentRequest();

        if (null !== $request) {
            $this->sessionStrategy->onAuthentication($request, $token);

            if (null !== $response && null !== $this->rememberMeService) {
                $this->rememberMeService->loginSuccess($request, $response, $token);
            }
        }

        $this->tokenStorage->setToken($token);

    }

    public function getDefaultFirewall()
    {
        return $this->parameters['default_firewall'];
    }

    public function add(UserInterface $user)
    {
        $this->update($user);

        $event = new UserEvent(UserEvent::TYPE_CREATED, $user);
        $this->eventDispatcher->dispatch($event);
    }

    public function register(UserInterface $user, $config, $action)
    {
        $user->setConfirmationToken($this->tokenGenerator->generateToken());
        $this->add($user);

        $this->sendRegistrationConfirmMail($user, $config, $action);
    }

    public function activate(UserInterface $user)
    {
        $this->enable($user);
        $this->update($user, false);

        $event = new UserEvent(UserEvent::TYPE_ACTIVATED, $user);
        $this->eventDispatcher->dispatch($event);
    }

    public function confirm(UserInterface $user, $config, $action)
    {
        $this->enable($user);
        $this->update($user, false);

        $event = new UserEvent(UserEvent::TYPE_REGISTRATION_CONFIRMED, $user);
        $this->eventDispatcher->dispatch($event);

        $this->sendConfirmationMail($user, $config, $action);
    }

    public function resetPassword(UserInterface $user, $config, $action)
    {
        $user->setConfirmationToken($this->tokenGenerator->generateToken());
        $this->update($user, false);

        $event = new UserEvent(UserEvent::TYPE_PASSWORD_RESET_REQUESTED, $user);
        $this->eventDispatcher->dispatch($event);

        $this->sendResetPasswordMail($user, $config, $action);
    }

    private function enable(UserInterface $user)
    {
        $user->setEnabled(true);
        $user->setConfirmationToken(null);
    }

    public function changePassword(UserInterface $user)
    {
        $user->setConfirmationToken(null);
        $this->update($user, false);

        $event = new UserEvent(UserEvent::TYPE_PASSWORD_CHANGED, $user);
        $this->eventDispatcher->dispatch($event);
    }

    public function changeEmail(UserInterface $user, $newEmail, $config, $action)
    {
        $oldEmail = $user->getEmail();
        $context = [
            'old_email' => $oldEmail,
            'new_email' => $newEmail,
        ];

        if ($this->sendMail($newEmail, $config, $action, $context)) {
            $user->setEmail($newEmail);
            $this->userMapper->setUsername($user);

            $event = new UserEvent(UserEvent::TYPE_EMAIl_CHANGED, $user);
            $this->eventDispatcher->dispatch($event);

            $this->update($user, false);

            // try to send confirmation to old email address also
            $this->sendMail($oldEmail, $config, $action, $context);

            return true;
        }


        return false;
    }

    public function update(UserInterface $user, $persist = true, $flush = true)
    {
        $this->updateUsername($user);
        $this->updatePassword($user);

        if ($persist) {
            $this->entityManager->persist($user);
        }
        if ($flush) {
            $this->entityManager->flush();
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

    public function createForm($config, $action, ?UserInterface $user = null, array $options = []): FormInterface
    {
        $formConfig = $this->getConfig($config, $action, 'form');
        $options = array_merge($formConfig['options'], $options);

        return $this->formFactory->create($formConfig['class'], $user, $options);
    }

    public function getConfig($config, $action = null, $item = null, $fallback = null)
    {
        if (!isset($this->config[$config])) {
            if ($fallback === null) {
                throw new OptionDefinitionException(sprintf('Could not find config "%s"', $config));
            }

            return $fallback;
        }

        $options = $this->config[$config];

        if ($action) {
            if (!isset($options[$action])) {
                if ($fallback === null) {
                    throw new OptionDefinitionException(sprintf('Could not find action "%s" in "%s"', $action, $config));
                }

                return $fallback;
            }
            $options = $options[$action];
            $options = array_merge([
                'content_type' => Message::CONTENT_TYPE_PLAIN,
                'mail_from' => $this->mail['from'],
                'sender_name' => $this->mail['sender_name'],
            ], $options);

            if ($item) {
                if (!isset($options[$item])) {
                    if ($fallback === null) {
                        throw new OptionDefinitionException(sprintf('Could not find item "%s" in "%s.%s"', $item, $config, $action));
                    }

                    return $fallback;
                }

                return $options[$item];
            }

            return $options;
        }

        return $options;
    }

    public function getTemplate($config, $action)
    {
        return $this->getConfig($config, $action, 'template');
    }

    public function getRedirectRoute($config, $action, $fallback = false)
    {
        return $this->getConfig($config, $action, 'redirect_route', $fallback);
    }

    public function getStylesheets($config, $action, $fallback = [])
    {
        return $this->getConfig($config, $action, 'stylesheets', $fallback);
    }

    public function getJavascripts($config, $action, $fallback = [])
    {
        return $this->getConfig($config, $action, 'javascripts', $fallback);
    }

    private function getMailSubject($options)
    {
        return $this->trans($options['mail_subject'], [], $options['translation_domain']);
    }

    public function mapValues(UserInterface $user, array $values)
    {
        $this->userMapper->mapValues($user, $values);
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

    private function sendRegistrationConfirmMail(UserInterface $user, $config, $action)
    {
        $options = $this->getConfig($config, $action);
        $message = $this->createUserMessage($user, $options, [
            'user' => $user,
            'confirmation_url' => $this->router->generate($options['confirmation_route'], ['token' => $user->getConfirmationToken()], RouterInterface::ABSOLUTE_URL)
        ]);
        $this->mailerManager->sendMessage($message);
    }

    private function sendResetPasswordMail(UserInterface $user, $config, $action)
    {
        $options = $this->getConfig($config, $action);
        $message = $this->createUserMessage($user, $options, [
            'user' => $user,
            'confirmation_url' => $this->router->generate($options['confirmation_route'], ['token' => $user->getConfirmationToken()], RouterInterface::ABSOLUTE_URL)
        ]);
        $this->mailerManager->sendMessage($message);
    }

    private function sendConfirmationMail(UserInterface $user, $config, $action, array $context = [])
    {
        $options = $this->getConfig($config, $action);

        $context = array_merge([
            'user' => $user
        ], $context);
        $message = $this->createUserMessage($user, $options, $context);
        $this->mailerManager->sendMessage($message);
    }

    private function sendMail(string $to, $config, $action, array $context = []): bool
    {
        $options = $this->getConfig($config, $action);

        $message = $this->createMessage($to, $options, $context);
        return 0 !== $this->mailerManager->sendMessage($message);
    }

    private function createUserMessage(UserInterface $user, array $options, array $context): Message
    {
        return $this->createMessage($user->getEmail(), $options, $context);
    }

    private function createMessage(string $to, array $options, array $context): Message
    {
        $message = $this->mailerManager->createMessage();
        $message->setSubject($this->getMailSubject($options));
        $message->setTemplate($options['mail_template']);
        $message->setTo($to);
        $message->setFrom($options['mail_from']);
        $message->setSenderName($options['sender_name']);
        $message->setContentType($options['content_type']);
        $message->setContext($context);

        return $message;
    }

    private function trans(string $id, array $parameters = [], string $domain = null, string $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    private function createToken($firewall, UserInterface $user)
    {
        return new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
    }
}
