<?php


namespace Enhavo\Bundle\NewsletterBundle\Newsletter;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Entity\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Entity\Tracking;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;

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

    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer, $from, $templates, TokenGeneratorInterface $tokenGenerator)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templates = $templates;
        $this->from = $from;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function send(Newsletter $newsletter)
    {
        if($newsletter->getSent()) {
            return;
        }

        $subscribers = $this->em
            ->getRepository('EnhavoNewsletterBundle:Subscriber')
            ->findBy(array('active' => true));

        foreach ($subscribers as $subscriber) {
            $this->updateReceiver($subscriber);
            $message = new \Swift_Message();
            $message
                ->setSubject($newsletter->getSubject())
                ->setContentType("text/html")
                ->setFrom($this->from)
                ->setTo($subscriber->getEmail())
                ->setBody($this->render($newsletter));
            $this->container->get('mailer')->send($message);
        }

        $newsletter->setSent(true);
        $this->em->persist($newsletter);
        $this->em->flush();
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

    public function render(NewsletterInterface $newsletter)
    {
        $templateManager = $this->container->get('enhavo_app.template.manager');
        $template = $this->getTemplate($newsletter->getTemplate());
        $content = $this->container->get('templating')->render($templateManager->getTemplate($template), [
            'resource' => $newsletter
        ]);
        return $content;
    }

    public function getTemplate(?string $key): string
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

    public function updateReceiver(Subscriber $subscriber) {
        $receiver = $this->em
            ->getRepository('EnhavoNewsletterBundle:Receiver')->findOneBy([
           'subscriber' => $subscriber
        ]);

        if (!$receiver) {
            $receiver = new Receiver();
            $receiver->setToken($this->tokenGenerator->generateToken());
        }

        $receiver->setEMail($subscriber->getEmail());
        $receiver->setSentAt(new \DateTime());
        // toDo: which Parameters usefull?
        //        $parameters = $subscriber->getParameters();
        //        $receiver->setParameters(json_encode($parameters));
        $receiver->setSubscriber($subscriber);

        $tracking = new Tracking();
        $tracking->setDate(new \DateTime());
        $tracking->setReceiver($receiver);
        $tracking->setType('enhavo-newsletter');

        $this->em->persist($receiver);
        $this->em->persist($tracking);
//        $this->em->flush();
    }

    public function addTracking() {

    }
}
