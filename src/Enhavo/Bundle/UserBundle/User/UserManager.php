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
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserManager
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var MailerManager */
    private $mailerManager;

    /** @var RepositoryInterface */
    private $userRepository;

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

    /** @var array */
    private $config;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param MailerManager $mailerManager
     * @param RepositoryInterface $userRepository
     * @param TokenGeneratorInterface $tokenGenerator
     * @param TranslatorInterface $translator
     * @param FormFactoryInterface $formFactory
     * @param EncoderFactoryInterface $encoderFactory
     * @param RouterInterface $router
     * @param array $config
     */
    public function __construct(EntityManagerInterface $entityManager, MailerManager $mailerManager, RepositoryInterface $userRepository, TokenGeneratorInterface $tokenGenerator, TranslatorInterface $translator, FormFactoryInterface $formFactory, EncoderFactoryInterface $encoderFactory, RouterInterface $router, array $config)
    {
        $this->entityManager = $entityManager;
        $this->mailerManager = $mailerManager;
        $this->userRepository = $userRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->translator = $translator;
        $this->formFactory = $formFactory;
        $this->encoderFactory = $encoderFactory;
        $this->router = $router;
        $this->config = $config;
    }

    /**
     * @param $token
     * @return UserInterface|object|null
     */
    public function findByConfirmationToken($token): ?UserInterface
    {
        return $this->userRepository->findOneBy([
            'confirmationToken' => $token,
        ]);
    }

    public function updatePassword(UserInterface $user)
    {
        $this->hashPassword($user);
    }

    public function getTemplate($config, $action)
    {
        return $this->getConfig($config, $action, 'template');
    }

    public function getRedirectRoute($config, $action)
    {
        return $this->getConfig($config, $action, 'redirect_route');
    }

    public function updateUser(UserInterface $user, $persist = true, $flush = true)
    {
        if ($persist) {
            $this->entityManager->persist($user);
        }
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function createForm($config, $action, ?UserInterface $user, array $options = []): FormInterface
    {
        $formConfig = $this->getConfig($config, $action, 'form');
        $options = array_merge($formConfig['options'], $options);

        return $this->formFactory->create($formConfig['class'], $user, $options);
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

    public function sendRegistrationConfirmMail(UserInterface $user, $config)
    {
        $user->setConfirmationToken($this->tokenGenerator->generateToken());
        $options = $this->getConfig($config, 'register');
        $message = $this->createMessage($user, $options, [
            'user' => $user,
            'confirmation_url' => $this->router->generate($options['confirmation_route'], ['token' => $user->getConfirmationToken()], RouterInterface::ABSOLUTE_URL)
        ]);
        $this->mailerManager->sendMessage($message);

        $this->updateUser($user, false);
    }

    public function getConfig($config, $action = null, $item = null)
    {
        if (!isset($this->config[$config])) {
            throw new OptionDefinitionException(sprintf('Could not find config "%s"', $config));
        }

        $options = $this->config[$config];

        if ($action) {
            if (!isset($options[$action])) {
                throw new OptionDefinitionException(sprintf('Could not find action "%s" in "%s"', $action, $config));
            }
            $options = $options[$action];
            $options = array_merge([
                'content_type' => Message::CONTENT_TYPE_HTML,
                'mail_from' => 'todo@enhavo.com',
                'sender_name' => 'enhavo todo',
            ], $options);

            if ($item) {
                if (!isset($options[$item])) {
                    throw new OptionDefinitionException(sprintf('Could not find item "%s" in "%s.%s"', $item, $config, $action));
                }

                return $options[$item];
            }

            return $options;
        }

        return $options;
    }

    private function sendResetPasswordMail(UserInterface $user, array $options)
    {
        $message = $this->createMessage($user, $options);
        $this->mailerManager->sendMessage($message);
    }

    private function sendConfirmationMail(UserInterface $user, array $options)
    {
        $message = $this->createMessage($user, $options, [
            'user' => $user,
        ]);
        $this->mailerManager->sendMessage($message);
    }

    private function createMessage(UserInterface $user, array $options, array $context): Message
    {
        $message = $this->mailerManager->createMessage();
        $message->setSubject($this->getMailSubject($options));
        $message->setTemplate($options['mail_template']);
        $message->setTo($user->getEmail());
        $message->setFrom($options['mail_from']);
        $message->setSenderName($options['sender_name']);
        $message->setContentType($options['content_type']);
        $message->setContext($context);

        return $message;
    }

    private function getMailSubject($options)
    {
        return $this->trans($options['mail_subject'], [], $options['translation_domain']);
    }

    private function trans(string $id, array $parameters = [], string $domain = null, string $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

}
