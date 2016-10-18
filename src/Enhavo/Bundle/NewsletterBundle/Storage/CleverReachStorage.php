<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 17/10/16
 * Time: 18:31
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class CleverReachStorage implements StorageInterface
{
    protected $cleverReachClient;

    public function __construct(ContainerInterface $container)
    {
        $this->cleverReachClient = $container->get('enhavo_newsletter.cleverreach_client');
    }

    public function saveSubscriber(SubscriberInterface $subscriber)
    {
        $this->cleverReachClient->saveSubscriber($subscriber->getEmail());
    }

    public function exists(SubscriberInterface $subscriber)
    {
        return $this->cleverReachClient->exists($subscriber->getEmail());
    }

    public function getType()
    {
        return 'clever_reach';
    }
}