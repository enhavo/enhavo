<?php

namespace Enhavo\Bundle\NewsletterBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Loader;
use Enhavo\Bundle\AppBundle\Event\RouteBuilderEvent;


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
            'enhavo_newsletter.subscriber.pre_create' => array('onPreCreate', 0),
            'enhavo_newsletter.subscriber.post_create' => array('onPostCreate', 0),
            'enhavo_newsletter.newsletter.build_index_route' => array('onBuildIndexRoute', 0),
            'enhavo_newsletter.newsletter.build_create_route' => array('onBuildCreateRoute', 0),
            'enhavo_newsletter.newsletter.build_edit_route' => array('onBuildEditRoute', 0),
            'enhavo_newsletter.newsletter.build_table_route' => array('onBuildTableRoute', 0),
        );
    }

    public function onBuildIndexRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setTemplate('EnhavoNewsletterBundle:Resource:index.html.twig');
    }

    public function onBuildCreateRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setTemplate('EnhavoNewsletterBundle:Resource:create.html.twig');
    }

    public function onBuildEditRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setTemplate('EnhavoNewsletterBundle:Resource:edit.html.twig');
    }

    public function onBuildTableRoute(RouteBuilderEvent $event)
    {
        $event->getBuilder()->setTemplate('EnhavoNewsletterBundle:Resource:table.html.twig');
    }

    public function onPreCreate(GenericEvent $event)
    {
        $subscriber = $event->getSubject();

        $subscriber->setCreated(new \DateTime());

        //Token
        $tokenId = time();
        $tokenId = $tokenId.$subscriber->getEmail();
        $token = $this->get('security.csrf.token_manager')->getToken($tokenId)->getValue();

        $subscriber->setToken($token);
        $subscriber->setActive(false);
    }

    public function onPostCreate(GenericEvent $event)
    {
        $subscriber = $event->getSubject();
        $email = $subscriber->getEmail();
        $code = $subscriber->getToken();

        $router = $this->container->get('router');
        $link = $router->generate('enhavo_newsletter_subscriber_activation', array('code' => $code), true);
        $text = $this->render($this->subscriber['template'], array(
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