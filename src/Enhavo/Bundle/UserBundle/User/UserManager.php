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
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserManager
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var MailerManager */
    private $mailerManager;

    /** @var TokenGeneratorInterface */
    private $tokenGenerator;

    /** @var TranslatorInterface */
    private $translator;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var array */
    private $config;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param MailerManager $mailerManager
     * @param TokenGeneratorInterface $tokenGenerator
     * @param TranslatorInterface $translator
     * @param FormFactoryInterface $formFactory
     * @param EncoderFactoryInterface $encoderFactory
     * @param array $config
     */
    public function __construct(EntityManagerInterface $entityManager, MailerManager $mailerManager, TokenGeneratorInterface $tokenGenerator, TranslatorInterface $translator, FormFactoryInterface $formFactory, EncoderFactoryInterface $encoderFactory, array $config)
    {
        $this->entityManager = $entityManager;
        $this->mailerManager = $mailerManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->translator = $translator;
        $this->formFactory = $formFactory;
        $this->encoderFactory = $encoderFactory;
        $this->config = $config;
    }


    public function register(UserInterface $user, array $options = [])
    {

    }

    public function confirm(UserInterface $user, array $options = [])
    {

    }

    public function resetPassword(UserInterface $user, array $options = [])
    {

    }

    public function updatePassword(UserInterface $user)
    {
        $this->hashPassword($user);
    }

    public function getTemplate($config)
    {
        return $this->config[$config]['template'];
    }

    public function getSuccessTemplate($config)
    {
        return $this->config[$config]['success_template'];
    }

    private function getMailSubject($options)
    {
        return $this->trans($options['mail_subject'], [], $options['translation_domain']);
    }


    public function createForm($config, ?UserInterface $user, array $options = []): FormInterface
    {
        $formConfig = $this->config[$config]['form'];
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

    private function sendRegistrationConfirmMail(UserInterface $user, array $options)
    {
        $message = $this->createMessage($user, $options);
        $this->mailerManager->sendMessage($message);
    }

    private function sendResetPasswordMail(UserInterface $user, array $options)
    {
        $message = $this->createMessage($user, $options);
        $this->mailerManager->sendMessage($message);
    }

    private function sendConfirmationMail(UserInterface $user, array $options)
    {
        $message = $this->createMessage($user, $options);
        $this->mailerManager->sendMessage($message);
    }

    private function createMessage(UserInterface $user, array $options): Message
    {
        $message = $this->mailerManager->createMessage();
        $message->setSubject($this->getMailSubject($options));
        $message->setTemplate($options['mail_template']);
        $message->setTo($user->getEmail());
        $message->setFrom($options['mail_from']);
        $message->setSenderName($options['sender_name']);
        $message->setContentType($options['content_type']);
        $message->setContext([
            'user' => $user
        ]);

        return $message;
    }

    private function trans(string $id, array $parameters = [], string $domain = null, string $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}
