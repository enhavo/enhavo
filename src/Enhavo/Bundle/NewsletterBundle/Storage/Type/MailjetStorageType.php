<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage\Type;

use Enhavo\Bundle\NewsletterBundle\Client\MailjetClient;
use Enhavo\Bundle\NewsletterBundle\Exception\InsertException;
use Enhavo\Bundle\NewsletterBundle\Exception\NoGroupException;
use Enhavo\Bundle\NewsletterBundle\Model\GroupAwareInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\AbstractStorageType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailjetStorageType extends AbstractStorageType
{
    /**
     * @var MailjetClient
     */
    protected $client;

    /**
     * CleverReachStorageType constructor.
     */
    public function __construct($cleverReachClient)
    {
        $this->client = $cleverReachClient;
    }

    /**
     * @throws InsertException
     * @throws NoGroupException
     *
     * @return mixed|void
     */
    public function saveSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $this->client->init($options['client_key'], $options['client_secret']);

        if (!$this->exists($subscriber, $options)) {
            $this->client->saveSubscriber($subscriber, null);
        }
        $groups = $this->mapGroups($subscriber, $options['groups']);
        foreach ($groups as $group) {
            $this->client->addToGroup($subscriber, $group);
        }
    }

    public function removeSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $groups = $this->mapGroups($subscriber, $options['groups']);

        $this->client->init($options['client_key'], $options['client_secret']);

        if ($options['gdpr_delete']) {
            $this->client->gdprDelete($subscriber, $options['client_key'], $options['client_secret']);
        } else {
            foreach ($groups as $group) {
                if (!$this->client->exists($subscriber->getConfirmationToken(), $group)) {
                    continue;
                }
                $this->client->removeFromGroup($subscriber, $group);
            }
        }
    }

    public function getSubscriber(SubscriberInterface $subscriber, array $options): ?SubscriberInterface
    {
        $this->client->init($options['client_key'], $options['client_secret']);

        $response = $this->client->getSubscriber($subscriber->getEmail() ?? $subscriber->getConfirmationToken());

        if ($response) {
            $subscriber->setEmail($response['Email']);
            $subscriber->setConfirmationToken($response['ID']);
        } else {
            throw new NotFoundHttpException('No Subscriber found');
        }

        return $subscriber;
    }

    /**
     * @throws NoGroupException
     */
    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        $this->client->init($options['client_key'], $options['client_secret']);

        // subscriber has to be in ALL given groups to return true
        $groups = $this->mapGroups($subscriber, $options['groups']);
        foreach ($groups as $group) {
            if ($this->client->exists($subscriber->getEmail(), $group)) {
                return true;
            }
        }

        return false;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'groups' => [],
            'gdpr_delete' => false, // https://gdpr.eu/ https://dev.mailjet.com/email/guides/contact-management/#gdpr-delete-contacts
        ]);
        $resolver->setRequired([
            'client_key',
            'client_secret',
        ]);
    }

    private function mapGroups(SubscriberInterface $subscriber, $groups)
    {
        if ($subscriber instanceof GroupAwareInterface) {
            $groupsValues = $subscriber->getGroups();
            $groups = [];

            foreach ($groupsValues as $groupsValue) {
                $groups[] = $groupsValue->getCode();
            }
        }

        if (0 === count($groups)) {
            throw new NoGroupException('no groups given');
        }

        return $groups;
    }

    public static function getName(): ?string
    {
        return 'mailjet';
    }
}
