<?php

namespace Enhavo\Bundle\UserBundle\TwoFactor\Email;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\FrameworkBundle\Mailer\MailerManager;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Model\TwoFactorInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\AuthenticationContextInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorFormRendererInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorProviderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailTwoFactorProvider implements TwoFactorProviderInterface
{
    public function __construct(
        private readonly TwoFactorFormRendererInterface $formRenderer,
        private readonly EntityManagerInterface $entityManager,
        private readonly MailerManager $mailerManager,
        private readonly ConfigurationProvider $configurationProvider,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function beginAuthentication(AuthenticationContextInterface $context): bool
    {
        $user = $context->getUser();

        if ($user instanceof TwoFactorInterface) {
            if ($user->getTwoFactorMethod() === 'email') {
                return true;
            }
        }

        return false;
    }

    public function needsPreparation(): bool
    {
        return true;
    }

    public function prepareAuthentication(object $user): void
    {
        if (!$user instanceof TwoFactorInterface) {
            return;
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->setTwoFactorSecretData([
            'code' => $code,
            'expires_at' => (new \DateTimeImmutable('+10 minutes'))->getTimestamp(),
        ]);
        $this->entityManager->flush();

        if ($user instanceof UserInterface) {
            $this->sendMail($user, $code);
        }
    }

    private function sendMail(UserInterface $user, string $code): void
    {
        $configuration = $this->configurationProvider->getTwoFactorEmailConfiguration();

        if (!$configuration->isMailEnabled()) {
            return;
        }

        $message = $this->mailerManager->createMessage();
        $message->setSubject($this->translator->trans($configuration->getMailSubject(), [], $configuration->getTranslationDomain()));
        $message->setTemplate($configuration->getMailTemplate());
        $message->setTo($user->getEmail());
        $message->setFrom($configuration->getMailFrom() ?? $this->mailerManager->getDefaults()->getMailFrom());
        $message->setSenderName($this->translator->trans($configuration->getMailSenderName() ?? $this->mailerManager->getDefaults()->getMailSenderName(), [], $configuration->getTranslationDomain()));
        $message->setContentType($configuration->getMailContentType());
        $message->setContext([
            'user' => $user,
            'code' => $code,
        ]);

        $this->mailerManager->sendMessage($message);
    }

    public function validateAuthenticationCode(object $user, string $authenticationCode): bool
    {
        if (!$user instanceof TwoFactorInterface) {
            return false;
        }

        $data = $user->getTwoFactorSecretData();
        $code = $data['code'] ?? null;
        $expiresAt = $data['expires_at'] ?? null;

        if (empty($code) || empty($expiresAt)) {
            return false;
        }

        if (time() > $expiresAt) {
            return false;
        }

        return $code === $authenticationCode;
    }

    public function getFormRenderer(): TwoFactorFormRendererInterface
    {
        return $this->formRenderer;
    }
}
