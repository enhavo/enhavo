<?php


namespace Enhavo\Bundle\NewsletterBundle\Newsletter;

use Doctrine\ORM\EntityManagerInterface;
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
            ->setBody($this->render($receiver->getNewsletter(), $receiver->getParameters()));
        return $this->mailer->send($message);
    }

    public function sendTest(NewsletterInterface $newsletter, string $email)
    {
        $message = new \Swift_Message();
        $message
            ->setSubject($newsletter->getSubject())
            ->setContentType("text/html")
            ->setFrom($this->from)
            ->setTo($email)
            ->setBody($this->render($newsletter, $this->provider->getTestParameters()));
        return $this->mailer->send($message);
    }

    public function getTestParameters()
    {
        return $this->provider->getTestParameters();
    }

    public function render(NewsletterInterface $newsletter, array $parameters = [])
    {
        $templateManager = $this->container->get('enhavo_app.template.manager');
        $template = $this->getTemplate($newsletter->getTemplate());
        $content = $this->container->get('twig')->render($templateManager->getTemplate($template), [
            'resource' => $newsletter,
            'newsletter' => $newsletter,
            'parameters' => $parameters
        ]);

        $content = $this->parameterParser->parse($content, $parameters);

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
