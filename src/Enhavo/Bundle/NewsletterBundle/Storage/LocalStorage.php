<?php
/**
 * LocalStorage.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage;


use Doctrine\ORM\EntityManagerInterface;
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
        return $this->getSubscriber($subscriber->getEmail()) !== null;
    }

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