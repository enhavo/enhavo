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

use Enhavo\Bundle\NewsletterBundle\Client\CleverReachClient;
use Enhavo\Bundle\NewsletterBundle\Exception\ActivateException;
use Enhavo\Bundle\NewsletterBundle\Exception\InsertException;
use Enhavo\Bundle\NewsletterBundle\Exception\NoGroupException;
use Enhavo\Bundle\NewsletterBundle\Exception\NotFoundException;
use Enhavo\Bundle\NewsletterBundle\Exception\RemoveException;
use Enhavo\Bundle\NewsletterBundle\Exception\UpdateException;
use Enhavo\Bundle\NewsletterBundle\Model\CleverReachGroup;
use Enhavo\Bundle\NewsletterBundle\Model\GroupAwareInterface;
use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\AbstractStorageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class CleverReachStorageType extends AbstractStorageType
{
    /**
     * @var CleverReachClient
     */
    protected $client;

    /**
     * @param CleverReachClient $cleverReachClient
     */
    public function __construct($cleverReachClient)
    {
        $this->client = $cleverReachClient;
    }

    /**
     * @throws NoGroupException
     * @throws InsertException
     * @throws UpdateException
     * @throws ActivateException
     *
     * @return void
     */
    public function saveSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $groups = $this->mapGroups($subscriber, $options['groups']);

        $this->client->init($options['client_id'], $options['client_secret'], $options['attributes'], $options['global_attributes']);

        foreach ($groups as $group) {
            if ($this->client->exists($subscriber->getEmail(), $group)) {
                $this->client->updateSubscriber($subscriber, $group);
                $this->client->activateSubscriber($subscriber, $group);
            } else {
                $this->client->saveSubscriber($subscriber, $group);
            }
        }
    }

    /**
     * @throws NoGroupException
     * @throws RemoveException
     *
     * @return void
     */
    public function removeSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $groups = $this->mapGroups($subscriber, $options['groups']);

        $this->client->init($options['client_id'], $options['client_secret'], $options['attributes'], $options['global_attributes']);

        foreach ($groups as $group) {
            if (!$this->client->exists($subscriber->getEmail(), $group)) {
                continue;
            }
            $this->client->removeSubscriber($subscriber, $group);
        }
    }

    /**+
     * @param SubscriberInterface $subscriber
     * @param array $options
     * @return SubscriberInterface|null
     * @throws NotFoundException
     */
    public function getSubscriber(SubscriberInterface $subscriber, array $options): ?SubscriberInterface
    {
        $this->client->init($options['client_id'], $options['client_secret'], $options['attributes'], $options['global_attributes']);

        $response = $this->client->getSubscriber($subscriber->getEmail());

        $subscriber->setEmail($response['email']);
        $this->setAttributes($subscriber, $options['attributes'], $response['attributes']);
        $this->setAttributes($subscriber, $options['global_attributes'], $response['global_attributes']);

        return $subscriber;
    }

    /**
     * @throws NoGroupException
     */
    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        $this->client->init($options['client_id'], $options['client_secret'], $options['attributes'], $options['global_attributes']);

        // subscriber has to be in ALL given groups to return true
        $groups = $this->mapGroups($subscriber, $options['groups']);
        foreach ($groups as $group) {
            if (!$this->client->exists($subscriber->getEmail(), $group)) {
                return false;
            }
        }

        return true;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attributes' => [],
            'global_attributes' => [],
            'groups' => [],
        ]);
        $resolver->setRequired([
            'client_secret',
            'client_id',
        ]);
    }

    /**
     * @throws NoGroupException
     */
    public function getGroup($groupId, array $options): ?GroupInterface
    {
        $this->client->init($options['client_id'], $options['client_secret'], $options['attributes'], $options['global_attributes']);

        $response = $this->client->getGroup($groupId);

        if (isset($response['id'])) {
            return $this->createGroup($response);
        }

        throw new NoGroupException(sprintf('group with id "%s" does not exist', $groupId));
    }

    public function getGroups(array $options): array
    {
        $this->client->init($options['client_id'], $options['client_secret'], $options['attributes'], $options['global_attributes']);

        $response = $this->client->getGroups();

        $groups = [];
        foreach ($response as $item) {
            $groups[] = $this->createGroup($item);
        }

        return $groups;
    }

    private function createGroup(array $data): CleverReachGroup
    {
        $lastChanged = new \DateTime();
        $lastChanged->setTimestamp($data['last_changed']);
        $lastMailing = new \DateTime();
        $lastMailing->setTimestamp($data['last_mailing']);

        return new CleverReachGroup($data['id'], $data['name'], $data['id'], $lastChanged, $lastMailing);
    }

    /**
     * @throws NoGroupException
     *
     * @return array
     */
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

    private function setAttributes(SubscriberInterface $subscriber, array $attributes, array $values)
    {
        if (count($attributes) && count($values)) {
            $propertyAccessor = new PropertyAccessor();

            foreach ($values as $valueKey => $valueValue) {
                if (is_array($valueValue)) {
                    throw new \Exception('Not implemented');
                }
                if (isset($attributes[$valueKey])) {
                    $propertyAccessor->setValue($subscriber, $attributes[$valueKey], $valueValue);
                }
            }
        }
    }

    public static function getName(): ?string
    {
        return 'cleverreach';
    }
}
