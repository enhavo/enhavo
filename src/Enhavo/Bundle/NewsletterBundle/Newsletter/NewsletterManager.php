<?php


namespace Enhavo\Bundle\NewsletterBundle\Newsletter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Exception\SendException;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Provider\ProviderInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\AppBundle\Util\SecureUrlTokenGenerator;

/**
 * NewsletterManager.php
 *
 * @since 05/07/16
 * @author gseidel
 */
class NewsletterManager
{
    use ContainerAwareTrait;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $from;

    /**
     * @var array
     */
    private $templates;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * @var ParameterParserInterface
     */
    private $parameterParser;

    public function __construct(
        EntityManagerInterface $em,
        \Swift_Mailer $mailer,
        $from,
        $templates,
        SecureUrlTokenGenerator $tokenGenerator,
        LoggerInterface $logger,
        ProviderInterface $provider,
        ParameterParserInterface $parameterParser
    ) {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templates = $templates;
        $this->from = $from;
        $this->tokenGenerator = $tokenGenerator;
        $this->logger = $logger;
        $this->provider = $provider;
        $this->parameterParser = $parameterParser;
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

    public function sendTest(NewsletterInterface $newsletter, string $email)
    {
        $receivers = $this->provider->getTestReceivers($newsletter);
        foreach($receivers as $receiver) {
            $receiver->setEmail($email);
            $this->sendNewsletter($receiver);
        }
    }

    private function addAttachmentsToMessage($attachments, \Swift_Message $message) {
        /** @var FileInterface $attachment */
        foreach ($attachments as $attachment) {
            $attach = new \Swift_Attachment();
            $attach->setFilename($attachment->getFilename());
            $attach->setContentType($attachment->getMimeType());
            $attach->setBody($attachment);
            $message->attach($attach);
        }
    }

    public function renderPreview(NewsletterInterface $newsletter)
    {
        $receivers = $this->provider->getTestReceivers($newsletter);
        return $this->render($receivers[0]);
    }

    public function render(Receiver $receiver)
    {
        $templateManager = $this->container->get('enhavo_app.template.manager');
        $template = $this->getTemplate($receiver->getNewsletter()->getTemplate());
        $content = $this->container->get('twig')->render($templateManager->getTemplate($template), [
            'resource' => $receiver->getNewsletter(),
            'receiver' => $receiver,
        ]);

        $content = $this->parameterParser->parse($content, $receiver->getParameters());

        return $content;
    }

    private function getTemplate(?string $key): string
    {
        if($key === null) {
            if(count($this->templates) === 1) {
                $key = array_keys($this->templates)[0];
                return $this->templates[$key]['template'];
            }
            throw new \Exception(sprintf('No template found for key "%s"', $key));
        }
        return $this->templates[$key]['template'];
    }
}
