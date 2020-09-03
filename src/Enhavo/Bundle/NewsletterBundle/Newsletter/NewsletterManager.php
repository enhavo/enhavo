<?php


namespace Enhavo\Bundle\NewsletterBundle\Newsletter;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\NewsletterBundle\Exception\SendException;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Provider\ProviderTypeInterface;
use Enhavo\Component\Metadata\MetadataRepository;
use Psr\Log\LoggerInterface;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;

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
     * @var string
     */
    private $from;

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

    /** @var MetadataRepository */
    private $metadataRepository;

    /**
     * @var string
     */
    private $provider;

    /**
     * NewsletterManager constructor.
     * @param EntityManagerInterface $em
     * @param \Swift_Mailer $mailer
     * @param string $from
     * @param TokenGeneratorInterface $tokenGenerator
     * @param LoggerInterface $logger
     * @param NewsletterRenderer $renderer
     * @param MetadataRepository $metadataRepository
     * @param string $provider
     */
    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer, string $from, TokenGeneratorInterface $tokenGenerator, LoggerInterface $logger, NewsletterRenderer $renderer, MetadataRepository $metadataRepository, string $provider)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->from = $from;
        $this->tokenGenerator = $tokenGenerator;
        $this->logger = $logger;
        $this->renderer = $renderer;
        $this->metadataRepository = $metadataRepository;
        $this->provider = $provider;
    }


    /**
     * @param NewsletterInterface $newsletter
     * @throws SendException
     */
    public function prepare(NewsletterInterface $newsletter)
    {
        if($newsletter->isPrepared()) {
            throw new SendException(sprintf('Newsletter with id "%s" already prepared', $newsletter->getId()));
        }
        $receivers = $this->provider->getReceivers($newsletter);
        foreach($receivers as $receiver) {
            $this->em->persist($receiver);

            if(!$receiver->getToken()) {
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
        if(!$newsletter->isPrepared()) {
            throw new SendException(sprintf(
                'Newsletter with id "%s" is not prepared yet. Prepare the newsletter first before sending',
                $newsletter->getId())
            );
        }

        if($newsletter->isSent()) {
            throw new SendException(sprintf('Newsletter with id "%s" already sent', $newsletter->getId()));
        }

        $this->logger->info(sprintf('"%s" prepared receiver found', count($newsletter->getReceivers())));

        $newsletter->setState(NewsletterInterface::STATE_SENDING);

        $mailsSent = 0;

        foreach($newsletter->getReceivers() as $receiver) {
            if($mailsSent === $limit){
                break;
            }
            if(!$receiver->isSent()) {
                if($this->sendNewsletter($receiver)) {
                    $receiver->setSentAt(new \DateTime());
                    $this->em->flush();
                    $mailsSent++;
                }
            }
        }

        $sent = true;
        foreach($newsletter->getReceivers() as $receiver) {
            if(!$receiver->isSent()) {
                $sent = false;
                break;
            }
        }

        if($sent) {
            $newsletter->setState(NewsletterInterface::STATE_SENT);
            $newsletter->setFinishAt(new \DateTime());
            $this->em->flush();
        }
        return $mailsSent;
    }

    private function sendNewsletter(Receiver $receiver)
    {
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
        $receivers = $this->provider->getTestReceivers($newsletter);
        foreach($receivers as $receiver) {
            $receiver->setEmail($email);
            $success = $this->sendNewsletter($receiver);
            if(!$success) {
                $return = false;
            }
        }
        return $return;
    }

    private function addAttachmentsToMessage($attachments, \Swift_Message $message) {
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
        // blutze: get local provider config

        $receivers = $this->provider->getTestReceivers($newsletter);
        return $this->render($receivers[0]);
    }

    public function render(Receiver $receiver)
    {
        return $this->renderer->render($receiver);
    }
}
