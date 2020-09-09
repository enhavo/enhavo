<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 17.12.18
 * Time: 12:21
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage\Type;

use Enhavo\Bundle\NewsletterBundle\Client\MailChimpClient;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Exception\MappingException;
use Enhavo\Bundle\NewsletterBundle\Exception\NoGroupException;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\AbstractStorageType;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailChimpStorageType extends AbstractStorageType
{

    /** @var MailChimpClient */
    private $client;

    /**
     * MailChimpStorageType constructor.
     * @param MailChimpClient $client
     */
    public function __construct(MailChimpClient $client)
    {
        $this->client = $client;
    }


    /**
     * @param SubscriberInterface $subscriber
     * @param array $options
     * @throws GuzzleException
     * @throws NoGroupException
     */
    public function saveSubscriber(SubscriberInterface $subscriber, array $options)
    {
        if (count($subscriber->getGroups()) === 0) {
            throw new NoGroupException('no groups given');
        }

        $this->client->init($options['url'], $options['api_key']);

        /** @var Group $group */
        foreach ($subscriber->getGroups() as $group) {
            $groupId = $this->mapGroup($group, $options['group_mapping']);
            $this->client->saveSubscriber($subscriber, $groupId);
        }

    }

    /**
     * @param SubscriberInterface $subscriber
     * @param array $options
     * @return bool
     * @throws GuzzleException
     * @throws \Enhavo\Bundle\NewsletterBundle\Exception\MappingException
     */
    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        // subscriber has to be in ALL given groups to return true
        if (count($subscriber->getGroups()) === 0) {
            return false;
        }

        $this->client->init($options['url'], $options['api_key']);

        /** @var Group $group */
        foreach ($subscriber->getGroups() as $group) {
            $groupId = $this->mapGroup($group, $options['groupMapping']);
            if (!$this->client->exists($subscriber->getEmail(), $groupId)) {
                return false;
            }
        }

        return true;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'url', 'api_key', 'group_mapping'
        ]);
    }

    private function mapGroup(Group $group, array $groupMapping)
    {
        if (isset($groupMapping[$group->getCode()])) {
            return $groupMapping[$group->getCode()];
        }

        throw new MappingException(
            sprintf('Mapping for group "%s" with code "%s" not exists.', $group->getName(), $group->getCode())
        );
    }

    public static function getName(): ?string
    {
        return 'mailchimp';
    }
}
