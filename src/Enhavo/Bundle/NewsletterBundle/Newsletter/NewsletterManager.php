<?php


namespace Enhavo\Bundle\NewsletterBundle\Newsletter;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Entity\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Entity\Tracking;
use Enhavo\Bundle\NewsletterBundle\Exception\SendException;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
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

    public function __construct(
        EntityManagerInterface $em,
        \Swift_Mailer $mailer,
        $from,
        $templates,
        SecureUrlTokenGenerator $tokenGenerator,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templates = $templates;
        $this->from = $from;
        $this->tokenGenerator = $tokenGenerator;
        $this->logger = $logger;
    }

    /**
     * @param Newsletter $newsletter
     * @param Group $group
     * @throws SendException
     */
    public function prepareReceivers(Newsletter $newsletter, Group $group)
    {
        if($newsletter->getSent()) {
            throw new SendException(sprintf('Newsletter with id "%s" already sent', $newsletter->getId()));
        }

        $subscribers = $group->getSubscriber();

        $newsletter->setSent(true);
        $newsletter->setSentAt(new \DateTime());

        foreach ($subscribers as $subscriber) {
            $this->createReceiver($subscriber, $newsletter);
        }

        $this->em->flush();
    }

    public function sendToReceivers()
    {
        $receivers = $this->em
            ->getRepository('EnhavoNewsletterBundle:Receiver')
            ->findBy([
                'sentAt' => null
            ]);

        $this->logger->info(sprintf('"%s" prepared receiver found', count($receivers)));
        foreach($receivers as $receiver) {
            $this->sendNewsletter($receiver);
            $this->em->flush();
        }
    }

    private function sendNewsletter(Receiver $receiver)
    {
        $message = new \Swift_Message();
        $message
            ->setSubject($receiver->getNewsletter()->getSubject())
            ->setContentType("text/html")
            ->setFrom($this->from)
            ->setTo($receiver->getEmail())
            ->setBody($this->render($receiver->getNewsletter()));

        $receiver->setSentAt(new \DateTime());
    }

    public function sendTest(NewsletterInterface $newsletter, string $email)
    {
        $message = new \Swift_Message();
        $message
            ->setSubject($newsletter->getSubject())
            ->setContentType("text/html")
            ->setFrom($this->from)
            ->setTo($email)
            ->setBody($this->render($newsletter));
        return $this->mailer->send($message);
    }

    private function render(NewsletterInterface $newsletter)
    {
        $templateManager = $this->container->get('enhavo_app.template.manager');
        $template = $this->getTemplate($newsletter->getTemplate());
        $content = $this->container->get('twig')->render($templateManager->getTemplate($template), [
            'resource' => $newsletter
        ]);
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

    private function createReceiver(Subscriber $subscriber, Newsletter $newsletter)
    {
        $receiver = new Receiver();
        $receiver->setToken($this->tokenGenerator->generateToken());
        $receiver->setEMail($subscriber->getEmail());
        $receiver->setSubscriber($subscriber);
        $receiver->setNewsletter($newsletter);
        $this->addTracking($receiver,'prepared');

        $this->em->persist($receiver);
    }

    private function addTracking(Receiver $receiver, $type)
    {
        $tracking = new Tracking();
        $tracking->setDate(new \DateTime());
        $tracking->setType($type);
        $receiver->addTracking($tracking);
    }
}
