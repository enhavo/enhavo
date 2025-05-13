<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Newsletter;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Mailer\Attachment;
use Enhavo\Bundle\AppBundle\Mailer\MailerManager;
use Enhavo\Bundle\AppBundle\Mailer\Message;
use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Exception\SendException;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Provider\ProviderInterface;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Twig\Environment;

/**
 * NewsletterManager.php
 *
 * @since 05/07/16
 *
 * @author gseidel
 */
class NewsletterManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MailerManager $mailerManager,
        private readonly TokenGeneratorInterface $tokenGenerator,
        private readonly LoggerInterface $logger,
        private readonly Environment $twig,
        private readonly TemplateResolver $templateResolver,
        private readonly ParameterParser $parameterParser,
        private readonly ProviderInterface $provider,
        private readonly NormalizerInterface $normalizer,
        private readonly string $from,
        private readonly array $templates,
    ) {
    }

    /**
     * @throws SendException
     */
    public function prepare(NewsletterInterface $newsletter): void
    {
        if ($newsletter->isPrepared()) {
            throw new SendException(sprintf('Newsletter with id "%s" already prepared', $newsletter->getId()));
        }

        $receivers = $this->provider->getReceivers($newsletter);

        foreach ($receivers as $receiver) {
            $this->em->persist($receiver);

            if (!$receiver->getToken()) {
                $receiver->setToken($this->tokenGenerator->generateToken());
            }

            $receiver->setNewsletter($newsletter);
        }
        $newsletter->setState(NewsletterInterface::STATE_PREPARED);
        $newsletter->setStartAt(new \DateTime());
        $this->em->flush();
    }

    public function send(NewsletterInterface $newsletter, $limit = null)
    {
        if (!$newsletter->isPrepared()) {
            throw new SendException(sprintf('Newsletter with id "%s" is not prepared yet. Prepare the newsletter first before sending', $newsletter->getId()));
        }

        if ($newsletter->isSent()) {
            throw new SendException(sprintf('Newsletter with id "%s" already sent', $newsletter->getId()));
        }

        $this->logger->info(sprintf('"%s" prepared receiver found', count($newsletter->getReceivers())));

        $newsletter->setState(NewsletterInterface::STATE_SENDING);

        $mailsSent = 0;

        foreach ($newsletter->getReceivers() as $receiver) {
            if ($mailsSent === $limit) {
                break;
            }
            if (!$receiver->isSent()) {
                $this->sendNewsletter($receiver);
                $receiver->setSentAt(new \DateTime());
                $this->em->flush();
                ++$mailsSent;
            }
        }

        $sent = true;
        foreach ($newsletter->getReceivers() as $receiver) {
            if (!$receiver->isSent()) {
                $sent = false;
                break;
            }
        }

        if ($sent) {
            $newsletter->setState(NewsletterInterface::STATE_SENT);
            $newsletter->setFinishAt(new \DateTime());
            $this->em->flush();
        }

        return $mailsSent;
    }

    private function sendNewsletter(Receiver $receiver): void
    {
        $newsletter = $receiver->getNewsletter();
        $message = $this->createMessage($this->from, '', $receiver->getEmail(), $newsletter->getSubject(), $this->getTemplate($newsletter->getTemplate()), [
            'resource' => $newsletter,
            'receiver' => $receiver,
        ], 'text/html');

        $message->setContent($this->render($receiver));

        if (!empty($receiver->getNewsletter()->getAttachments())) {
            $this->addAttachmentsToMessage($receiver->getNewsletter()->getAttachments(), $message);
        }

        $this->sendMessage($message);
    }

    public function sendTest(NewsletterInterface $newsletter, ?string $email = null): void
    {
        $receivers = $this->provider->getTestReceivers($newsletter);

        foreach ($receivers as $receiver) {
            $receiver->setNewsletter($newsletter);
            $receiver->setEmail($email);
            $this->sendNewsletter($receiver);
        }
    }

    private function addAttachmentsToMessage($files, Message $message): void
    {
        /** @var FileInterface $file */
        foreach ($files as $file) {
            $message->addAttachment(new Attachment($file));
        }
    }

    public function renderPreview(NewsletterInterface $newsletter): string
    {
        $receivers = $this->provider->getTestReceivers($newsletter);
        foreach ($receivers as $receiver) {
            $receiver->setNewsletter($newsletter);
        }

        return $this->render($receivers[0]);
    }

    public function render(Receiver $receiver): string
    {
        $template = $this->getTemplate($receiver->getNewsletter()->getTemplate());
        $content = $this->twig->render($this->templateResolver->resolve($template), [
            'resource' => $this->normalizer->normalize($receiver->getNewsletter(), null, ['groups' => 'endpoint']),
            'receiver' => $this->normalizer->normalize($receiver, null, ['groups' => 'endpoint']),
        ]);

        $parameters = $receiver->getParameters();
        if ($parameters) {
            $content = $this->parameterParser->parse($content, $receiver->getParameters());
        }

        return $content;
    }

    public function createMessage(string $from, string $senderName, string $to, string $subject, string $template, array $context, string $contentType = 'text/html'): Message
    {
        $message = $this->mailerManager->createMessage();
        $message->setSubject($subject);
        $message->setFrom($from);
        $message->setSenderName($senderName);
        $message->setTo($to);
        $message->setTemplate($template);
        $message->setContext($context);
        $message->setContentType($contentType);

        return $message;
    }

    public function sendMessage(Message $message): void
    {
        $this->mailerManager->sendMessage($message);
    }

    public function getTemplate(?string $key): string
    {
        if (null === $key) {
            if (1 === count($this->templates)) {
                $key = array_keys($this->templates)[0];

                return $this->templates[$key]['template'];
            }
            throw new \Exception(sprintf('No template found for key "%s"', $key));
        }

        return $this->templates[$key]['template'];
    }

    public function update(NewsletterInterface $newsletter): void
    {
        if (null === $newsletter->getSlug()) {
            $newsletter->setSlug(Slugifier::slugify($newsletter->getSubject()));
        }
    }
}
