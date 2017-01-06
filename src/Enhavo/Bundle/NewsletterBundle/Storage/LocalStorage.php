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

    public function exists(SubscriberInterface $subscriber)
    {
        // subscriber has to be in ALL given groups to return true
        if ($this->getSubscriber($subscriber->getEmail()) === null) {
            return false;
        }

        $groupsSubscriberIsIn = $this->getSubscriber($subscriber->getEmail())->getGroups()->getValues();

        $subscriberGroupNames = [];
        /**
         * @var $group Group
         */
        foreach ($groupsSubscriberIsIn as $group) {
            $subscriberGroupNames[] = $group->getName();
        }

        foreach ($subscriber->getGroups() as $group) {
            $group = $this->manager->getRepository(Group::class)->findOneBy(['name' => $group->getName()]);
            if ($group === null) {
                return false;
            }
            if (!in_array($group->getName(), $subscriberGroupNames)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $email string
     * @return SubscriberInterface|null
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