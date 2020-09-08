<?php


namespace Enhavo\Bundle\NewsletterBundle\Newsletter;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Exception\DeliveryException;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Subscribtion\SubscribtionManager;
use Psr\Log\LoggerInterface;
use Twig\Environment;

/**
 * NewsletterManager.php
 *
 * @since 05/07/16
 * @author gseidel
 */
class NewsletterManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var SubscribtionManager
     */
    private $subscribtionManager;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var NewsletterRenderer
     */
    private $renderer;

    /** @var Environment */
    private $twig;

    /** @var string */
    private $from;

    /**
     * NewsletterManager constructor.
     * @param EntityManagerInterface $em
     * @param \Swift_Mailer $mailer
     * @param SubscribtionManager $subscriberManager
     * @param TokenGeneratorInterface $tokenGenerator
     * @param LoggerInterface $logger
     * @param NewsletterRenderer $renderer
     * @param Environment $twig
     * @param array $from
     */
    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer, SubscribtionManager $subscriberManager, TokenGeneratorInterface $tokenGenerator, LoggerInterface $logger, NewsletterRenderer $renderer, Environment $twig, string $from)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->subscribtionManager = $subscriberManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->logger = $logger;
        $this->renderer = $renderer;
        $this->twig = $twig;
        $this->from = $from;
    }


    /**
     * @param NewsletterInterface $newsletter
     * @throws DeliveryException
     */
    public function prepare(NewsletterInterface $newsletter)
    {
        if ($newsletter->isPrepared()) {
            throw new DeliveryException(sprintf('Newsletter with id "%s" already prepared', $newsletter->getId()));
        }
        $subscribtion = $this->subscribtionManager->getSubscribtion('default');
        $receivers = $subscribtion->getStrategy()->getStorage()->getTestReceivers($newsletter);

        /** @var Receiver $receiver */
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
            throw new DeliveryException(sprintf(
                    'Newsletter with id "%s" is not prepared yet. Prepare the newsletter first before sending',
                    $newsletter->getId())
            );
        }

        if ($newsletter->isSent()) {
            throw new DeliveryException(sprintf('Newsletter with id "%s" already sent', $newsletter->getId()));
        }

        $this->logger->info(sprintf('"%s" prepared receiver found', count($newsletter->getReceivers())));

        $newsletter->setState(NewsletterInterface::STATE_SENDING);

        $mailsSent = 0;

        foreach ($newsletter->getReceivers() as $receiver) {
            if ($mailsSent === $limit) {
                break;
            }
            if (!$receiver->isSent()) {
                if ($this->sendNewsletter($receiver)) {
                    $receiver->setSentAt(new \DateTime());
                    $this->em->flush();
                    $mailsSent++;
                }
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

    private function sendNewsletter(Receiver $receiver)
    {
        /** @var \Swift_Message $message */
        $message = $this->mailer->createMessage();
        $message
            ->setSubject($receiver->getNewsletter()->getSubject())
            ->setContentType("text/html")
            ->setFrom($this->from)
            ->setTo($receiver->getEmail())
            ->setBody($this->render($receiver));

        if (!empty($receiver->getNewsletter()->getAttachments())) {
            $this->addAttachmentsToMessage($receiver->getNewsletter()->getAttachments(), $message);
        }

        return $this->mailer->send($message);
    }

    public function sendTest(NewsletterInterface $newsletter, string $email): bool
    {
        $return = true;
        $subscribtion = $this->subscribtionManager->getSubscribtion('default');
        $receivers = $subscribtion->getStrategy()->getStorage()->getTestReceivers($newsletter);

        foreach ($receivers as $receiver) {
            $receiver->setEmail($email);
            $success = $this->sendNewsletter($receiver);
            if (!$success) {
                $return = false;
            }
        }
        return $return;
    }

    private function addAttachmentsToMessage($attachments, \Swift_Message $message)
    {
        /** @var FileInterface $attachment */
        foreach ($attachments as $attachment) {
            $attach = new \Swift_Attachment();
            $attach->setFilename($attachment->getFilename());
            $attach->setContentType($attachment->getMimeType());
            $attach->setBody($attachment->getContent()->getContent());
            $message->attach($attach);
        }
    }

    public function renderPreview(NewsletterInterface $newsletter)
    {
        $subscribtion = $this->subscribtionManager->getSubscribtion('default');
        $receivers = $subscribtion->getStrategy()->getStorage()->getTestReceivers($newsletter);

        return $this->render($receivers[0]);
    }

    public function render(Receiver $receiver)
    {
        return $this->renderer->render($receiver);
    }

    public function createMessage(string $from, string $senderName, string $to, string $subject, string $template, array $context, string $contentType = 'text/html'): \Swift_Message
    {
        $message = new \Swift_Message();
        $message->setSubject($subject)
            ->setFrom($from, $senderName)
            ->setTo($to)
            ->setBody($this->renderTemplate($template, $context), $contentType);

        return $message;
    }

    public function sendMessage(\Swift_Message $message)
    {
        $this->mailer->send($message);
    }

    protected function renderTemplate($template, $context)
    {
        return $this->twig->render($template, $context);
    }

}
