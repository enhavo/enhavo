<?php
/**
 * @author blutze-media
 * @since 2021-10-13
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage\Type;

use Enhavo\Bundle\NewsletterBundle\Client\MailjetClient;
use Enhavo\Bundle\NewsletterBundle\Exception\InsertException;
use Enhavo\Bundle\NewsletterBundle\Exception\NoGroupException;
use Enhavo\Bundle\NewsletterBundle\Model\GroupAwareInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\AbstractStorageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailjetStorageType extends AbstractStorageType
{
    /**
     * @var MailjetClient
     */
    protected $client;

    /**
     * CleverReachStorageType constructor.
     * @param $cleverReachClient
     */
    public function __construct($cleverReachClient)
    {
        $this->client = $cleverReachClient;
    }

    /**
     * @param SubscriberInterface $subscriber
     * @param array $options
     * @return mixed|void
     * @throws InsertException
     * @throws NoGroupException
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

        foreach ($groups as $group) {
            if (!$this->client->exists($subscriber->getConfirmationToken(), $group)) {
                continue;
            }
            $this->client->removeFromGroup($subscriber, $group);
        }
    }

    public function getSubscriber(SubscriberInterface $subscriber, array $options): ?SubscriberInterface
    {
        $this->client->init($options['client_key'], $options['client_secret']);

        $response = $this->client->getSubscriber($subscriber->getEmail()??$subscriber->getConfirmationToken());

        $subscriber->setEmail($response['Email']);
        $subscriber->setConfirmationToken($response['ID']);

        return $subscriber;

    }

    /**
     * @param SubscriberInterface $subscriber
     * @param array $options
     * @return bool
     * @throws NoGroupException
     */
    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        $this->client->init($options['client_key'], $options['client_secret']);

        // subscriber has to be in ALL given groups to return true
        $groups = $this->mapGroups($subscriber, $options['groups']);
        foreach ($groups as $group) {
            if (!$this->client->exists($subscriber->getEmail(), $group)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'groups' => [],
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

        if (count($groups) === 0) { // todo: only if group is required in mailjet api?
            throw new NoGroupException('no groups given');
        }

        return $groups;
    }

    /**
     * @inheritDoc
     */
    public static function getName(): ?string
    {
        return 'mailjet';
    }
}
