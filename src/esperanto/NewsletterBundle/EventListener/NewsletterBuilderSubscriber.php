<?php

namespace esperanto\NewsletterBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use esperanto\AdminBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;


class NewsletterBuilderSubscriber extends Controller implements EventSubscriberInterface
{
    /**
     * @var Container
     */
    protected $container;
    private $subscriber;

    public function __construct(Container $container, $subscriber)
    {
        $this->subscriber = $subscriber;
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'esperanto_newsletter.subscriber.pre_create' => array('onPreCreate', 0),
            'esperanto_newsletter.subscriber.post_create' => array('onPostCreate', 0),
        );
    }

    public function onPreCreate(GenericEvent $event)
    {
        $subscriber = $event->getSubject();

        //Erstellungszeitpunkt in DB speichern
        $subscriber->setCreated(new \DateTime());

        //Token
        $tokenId = time();
        $tokenId = $tokenId.$subscriber->getEmail();
        $csrfToken = $this->get('security.csrf.token_manager')->getToken($tokenId)->getValue();

        //Token in DB speichern
        $subscriber->setToken($csrfToken);
        $subscriber->setActive(false);
    }

    public function onPostCreate(GenericEvent $event)
    {
        //Email senden
        $subscriber = $event->getSubject();
        $email = $subscriber->getEmail();
        $code = $subscriber->getToken();

        $router = $this->container->get('router');
        $link = $router->getContext()->getHost().$router->generate('esperanto_newsletter_emailactivation', array('code' => $code));
        $text = $this->render('esperantoNewsletterBundle:Default:'.$this->subscriber['template'], array(
            "link" => $link
        ));

        $message = \Swift_Message::newInstance()
            ->setSubject($this->subscriber['subject'])
            ->setFrom($this->subscriber['send_from'])
            ->setTo($email)
            ->setBody($text);

        $this->get('mailer')->send($message);
    }
}