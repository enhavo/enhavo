<?php
/**
 * SubscriberManager.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Subscriber;

use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\StorageInterface;
use Enhavo\Bundle\NewsletterBundle\Strategy\StrategyInterface;

class SubscriberManager
{
    /**
     * @var TypeCollector
     */
    private $strategyTypeCollector;

    /**
     * @var string
     */
    private $strategy;

    /**
     * @var TypeCollector
     */
    private $storageTypeCollector;

    /**
     * @var string
     */
    private $storage;

    public function __construct(
        TypeCollector $strategyTypeCollector,
        $strategy,
        TypeCollector $storageTypeCollector,
        $storage
    )
    {
        $this->strategyTypeCollector = $strategyTypeCollector;
        $this->strategy = $strategy;
        $this->storageTypeCollector = $storageTypeCollector;
        $this->storage = $storage;
    }

    /**
     * @return StrategyInterface
     * @throws \Enhavo\Bundle\AppBundle\Exception\TypeNotFoundException
     */
    public function getStrategy()
    {
        return $this->strategyTypeCollector->getType($this->strategy);
    }

    /**
     * @return StorageInterface
     * @throws \Enhavo\Bundle\AppBundle\Exception\TypeNotFoundException
     */
    public function getStorage()
    {
        return $this->storageTypeCollector->getType($this->storage);
    }

    public function addSubscriber(SubscriberInterface $subscriber)
    {
        $strategy = $this->getStrategy();
        $strategy->addSubscriber($subscriber);
    }

    public function saveSubscriber(SubscriberInterface $subscriber)
    {
        $storage = $this->getStorage();
        $storage->saveSubscriber($subscriber);
    }

    public function exists(SubscriberInterface $subscriber)
    {
        $strategy = $this->getStrategy();
        return $strategy->exists($subscriber);
    }

    public function handleExists(SubscriberInterface $subscriber)
    {
        $strategy = $this->getStrategy();
        return $strategy->handleExists($subscriber);
    }
}