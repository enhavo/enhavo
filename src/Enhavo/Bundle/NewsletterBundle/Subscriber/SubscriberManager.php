<?php
/**
 * SubscriberManager.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Subscriber;

use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\NewsletterBundle\Form\Resolver;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\StorageInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\StorageResolver;
use Enhavo\Bundle\NewsletterBundle\Strategy\StrategyInterface;
use Enhavo\Bundle\NewsletterBundle\Strategy\StrategyResolver;
use Enhavo\Bundle\NewsletterBundle\Event\SubscriberEvent;
use Enhavo\Bundle\NewsletterBundle\Event\NewsletterEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
     * @var StrategyResolver
     */
    private $strategyResolver;

    /**
     * @var TypeCollector
     */
    private $storageTypeCollector;

    /**
     * @var string
     */
    private $storage;

    /**
     * @var StorageResolver
     */
    private $storageResolver;

    /**
     * @var Resolver
     */
    private $formResolver;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        TypeCollector $strategyTypeCollector,
        $strategy,
        $strategyResolver,
        TypeCollector $storageTypeCollector,
        $storage,
        $storageResolver,
        $formResolver,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->strategyTypeCollector = $strategyTypeCollector;
        $this->strategy = $strategy;
        $this->strategyResolver = $strategyResolver;
        $this->storageTypeCollector = $storageTypeCollector;
        $this->storage = $storage;
        $this->storageResolver = $storageResolver;
        $this->formResolver = $formResolver;
        $this->eventDispatcher = $eventDispatcher;
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
     * @return StrategyInterface
     * @throws \Enhavo\Bundle\AppBundle\Exception\TypeNotFoundException
     */
    public function getStrategyByName($name)
    {
        return $this->strategyTypeCollector->getType($name);
    }

    /**
     * @return StorageInterface
     * @throws \Enhavo\Bundle\AppBundle\Exception\TypeNotFoundException
     */
    public function getStorage()
    {
        return $this->storageTypeCollector->getType($this->storage);
    }

    /**
     * @return StorageInterface
     * @throws \Enhavo\Bundle\AppBundle\Exception\TypeNotFoundException
     */
    public function getStorageByName($name)
    {
        return $this->storageTypeCollector->getType($name);
    }

    public function addSubscriber(SubscriberInterface $subscriber, $type = null)
    {
        $event = new SubscriberEvent($subscriber, $subscriber->getType());
        $this->eventDispatcher->dispatch(NewsletterEvents::EVENT_ADD_SUBSCRIBER, $event);

        if ($type === null) {
            $strategy = $this->getStrategy();
        } else {
            $strategy = $this->strategyResolver->resolve($type);
        }
        return $strategy->addSubscriber($subscriber, $type);
    }

    public function createSubscriber(SubscriberInterface $subscriber)
    {
        $event = new SubscriberEvent($subscriber, $subscriber->getType());
        $this->eventDispatcher->dispatch(NewsletterEvents::EVENT_CREATE_SUBSCRIBER, $event);
    }

    public function saveSubscriber(SubscriberInterface $subscriber, $type)
    {
        if ($type === null) {
            $storage = $this->getStorage();
        } else {
            $storage = $this->storageResolver->resolve($type);
        }
        $storage->saveSubscriber($subscriber);
    }

    public function exists(SubscriberInterface $subscriber, $type)
    {
        if ($type === null) {
            $strategy = $this->getStrategy();
        } else {
            $strategy = $this->strategyResolver->resolve($type);
        }
        return $strategy->exists($subscriber, $type);
    }

    public function handleExists(SubscriberInterface $subscriber)
    {
        $strategy = $this->getStrategy();
        return $strategy->handleExists($subscriber);
    }

    public function createForm(SubscriberInterface $subscriber)
    {

    }
}