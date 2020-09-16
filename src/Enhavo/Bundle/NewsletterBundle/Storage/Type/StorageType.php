<?php


namespace Enhavo\Bundle\NewsletterBundle\Storage\Type;


use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\StorageTypeInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StorageType extends AbstractType implements StorageTypeInterface
{
    public function getReceivers(NewsletterInterface $newsletter, array $options): array
    {
        return [];
    }

    public function getTestReceivers(NewsletterInterface $newsletter, array $options): array
    {
        $receiver = new Receiver();
        $receiver->setToken('__ID_TOKEN__');
        $receiver->setNewsletter($newsletter);
        $receiver->setParameters([
            'firstName' => 'Firstname',
            'lastName' => 'Lastname',
            'token' => '__TRACKING_TOKEN__',
            'type' => 'default'
        ]);

        return [$receiver];
    }

    public function saveSubscriber(SubscriberInterface $subscriber, array $options)
    {

    }

    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        return false;
    }

}
