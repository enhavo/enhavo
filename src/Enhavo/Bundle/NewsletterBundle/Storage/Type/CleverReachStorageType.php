<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 17/10/16
 * Time: 18:31
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage\Type;

use Enhavo\Bundle\NewsletterBundle\Client\CleverReachClient;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Exception\NoGroupException;
use Enhavo\Bundle\NewsletterBundle\Model\CleverReachGroup;
use Enhavo\Bundle\NewsletterBundle\Model\GroupAwareInterface;
use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\AbstractStorageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CleverReachStorageType extends AbstractStorageType
{
    /**
     * @var CleverReachClient
     */
    protected $client;

    public function __construct($cleverReachClient)
    {
        $this->client = $cleverReachClient;
    }

    public function saveSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $groups = $this->mapGroups($subscriber, $options['groups']);

        $this->client->init($options['client_id'], $options['client_secret'], $options['attributes'], $options['global_attributes']);

        foreach ($groups as $group) {
            if ($this->client->exists($subscriber->getEmail(), $group)) {
                continue;
            }
            $this->client->saveSubscriber($subscriber, $group);
        }
    }

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

    public function getGroup($groupId, array $options): ?GroupInterface
    {
        $this->client->init($options['client_id'], $options['client_secret'], $options['attributes'], $options['global_attributes']);

        $response = $this->client->getGroup($groupId);

        if (isset($response['id'])) {
            $lastChanged = new \DateTime();
            $lastChanged->setTimestamp($response['last_changed']);
            $lastMailing = new \DateTime();
            $lastMailing->setTimestamp($response['last_mailing']);

            return new CleverReachGroup($response['id'], $response['name'], $response['id'], $lastChanged, $lastMailing);
        }

        throw new NoGroupException(sprintf('group with id "%s" does not exist', $groupId));
    }

    public function getGroups(array $options): array
    {
        $this->client->init($options['client_id'], $options['client_secret'], $options['attributes'], $options['global_attributes']);

        $response = $this->client->getGroups();

        $groups = [];
        foreach ($response as $item) {

            $lastChanged = new \DateTime();
            $lastChanged->setTimestamp($item['last_changed']);
            $lastMailing = new \DateTime();
            $lastMailing->setTimestamp($item['last_mailing']);

            $groups[] = new CleverReachGroup($item['id'], $item['name'], $item['id'], $lastChanged, $lastMailing);
        }

        return $groups;
    }

    private function mapGroups(SubscriberInterface $subscriber, $groups)
    {
        if ($subscriber instanceof GroupAwareInterface) {
            /** @var Group[] $groupsValues */
            $groupsValues = $subscriber->getGroups()->getValues();
            $groups = [];

            foreach ($groupsValues as $groupsValue) {
                $groups[] = $groupsValue->getCode();
            }
        }

        if (count($groups) === 0) {
            throw new NoGroupException('no groups given');
        }

        return $groups;
    }

    public static function getName(): ?string
    {
        return 'cleverreach';
    }
}
