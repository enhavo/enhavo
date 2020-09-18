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
use Enhavo\Bundle\NewsletterBundle\Model\GroupAwareInterface;
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
        $groups = $this->getGroups($subscriber, $options['groups']);

        $this->client->init($options['url'], $options['api_key'], $options['body_parameters']);

        foreach ($groups as $group) {
            if ($this->client->exists($subscriber->getEmail(), $group)) {
                continue;
            }
            $this->client->saveSubscriber($subscriber, $group);
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
        $this->client->init($options['url'], $options['api_key'], $options['body_parameters']);

        // subscriber has to be in ALL given groups to return true
        $groups = $this->getGroups($subscriber, $options['groups']);
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
            'body_parameters' => [
                'email_address' => 'email'
            ],
            'groups' => [],
        ]);
        $resolver->setRequired([
            'url', 'api_key'
        ]);
    }

    private function getGroups(SubscriberInterface $subscriber, $groups)
    {
        if ($subscriber instanceof GroupAwareInterface) {
            /** @var Group[] $groupsValues */
            $groupsValues = $subscriber->getGroups()->getValues();
            $groups = [];

            foreach ($groupsValues as $groupsValue) {
                $groups[] = $groupsValue->getCode();
            }
        }

        if (count($groups) === 0) { // blutze: only if group is required in mailchimp api?
            throw new NoGroupException('no groups given');
        }

        return $groups;
    }

    public static function getName(): ?string
    {
        return 'mailchimp';
    }
}
