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
use Enhavo\Bundle\NewsletterBundle\Exception\InsertException;
use Enhavo\Bundle\NewsletterBundle\Exception\MappingException;
use Enhavo\Bundle\NewsletterBundle\Exception\NoGroupException;
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
//        if (count($subscriber->getGroups()) === 0) {
//            throw new NoGroupException('no groups given');
//        }

        $this->client->init($options['client_id'], $options['client_secret'], $options['postdata']);

        // blutze: if ($localSubscriber instanceof GroupAwareInterface) {
        //  gruppen können aus options oder über die im post enthaltenen groups gesetzt werden
        //  wenn am ende nicht wenigstens eine gruppe vorhanden, dann exception

//        $groups = $subscriber->getGroups()->getValues();
//        /** @var Group $group */
        foreach ($options['group_mapping'] as $key => $groupId) {
//            $groupId = $this->mapGroup($group, $options['group_mapping']);
            if ($this->client->exists($subscriber->getEmail(), $groupId)) {
                continue;
            }

            $this->client->saveSubscriber($subscriber, $groupId);
        }


    }

    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        $this->client->init($options['client_id'], $options['client_secret'], $options['postdata']);

        // subscriber has to be in ALL given groups to return true
        /** @var Group $group */
        foreach ($options['group_mapping'] as $local => $groupId) {
//            $groupId = $this->mapGroup($group, $options['group_mapping']);
            if (!$this->client->exists($subscriber->getEmail(), $groupId)) {
                return false;
            }
        }

        return true;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'postdata' => []
        ]);
        $resolver->setRequired([
            'client_secret', 'client_id', 'group_mapping'
        ]);
    }

    /**
     * @param Group $group
     * @param array $groupMapping
     * @return mixed
     * @throws MappingException
     */
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
        return 'cleverreach';
    }
}
