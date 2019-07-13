<?php


namespace Enhavo\Bundle\NewsletterBundle\Newsletter;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

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

    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer, $from, $templates)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templates = $templates;
        $this->from = $from;
    }

    public function send(Newsletter $newsletter)
    {
        if($newsletter->getSent()) {
            return;
        }

        $subscriber = $this->em
            ->getRepository('EnhavoNewsletterBundle:Subscriber')
            ->findBy(array('active' => true));

        for($i = 0; $i < count($subscriber); $i++)
        {
            $message = \Swift_Message::newInstance()
                ->setSubject($newsletter->getSubject())
                ->setContentType("text/html")
                ->setFrom($this->config['send_from'])
                ->setTo($subscriber[$i]->getEmail())
                ->setBody($newsletter->getText());
            $this->get('mailer')->send($message);
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
}
