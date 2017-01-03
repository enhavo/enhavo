<?php
/**
 * LocalStorage.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage;


use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class LocalStorage implements StorageInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    public function __construct($options, EntityManagerInterface $manager, RepositoryInterface $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    public function saveSubscriber(SubscriberInterface $subscriber)
    {
        $this->manager->persist($subscriber);
        $this->manager->flush();
    }

    public function exists(SubscriberInterface $subscriber, $groupNames = [])
    {
        // subscriber has to be in ALL given groups to return true

        if ($this->getSubscriber($subscriber->getEmail()) === null) {
            return false;
        }

        $groupsSubscriberIsIn = $this->getSubscriber($subscriber->getEmail())->getGroup()->getValues();

        $subscriberGroupNames = [];
        /**
         * @var $group Group
         */
        foreach ($groupsSubscriberIsIn as $group) {
            $subscriberGroupNames[] = $group->getName();
        }

        foreach ($groupNames as $groupName) {
            $group = $this->manager->getRepository(Group::class)->findOneBy(['name' => $groupName]);
            if ($group === null) {
                return false;
            }
            if (!in_array($groupName, $subscriberGroupNames)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $subscriber SubscriberInterface
     * @return object
     */
    public function getSubscriber($email)
    {
        return $this->repository->findOneBy([
            'email' => $email
        ]);
    }

    public function getType()
    {
        return 'local';
    }
}