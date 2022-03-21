<?php
/**
 * SubscriberManager.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Subscription;

use Enhavo\Bundle\NewsletterBundle\Event\SubscriberEvent;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;

class SubscriptionManager
{
    const DEFAULT_PROVIDER = 'local';

    /** @var FactoryInterface */
    private $storageFactory;

    /** @var FactoryInterface */
    private $strategyFactory;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var array */
    private $configuration;

    /**
     * SubscriptionManager constructor.
     * @param FactoryInterface $storageFactory
     * @param FactoryInterface $strategyFactory
     * @param FormFactoryInterface $formFactory
     * @param EventDispatcherInterface $eventDispatcher
     * @param array $configuration
     */
    public function __construct(FactoryInterface $storageFactory, FactoryInterface $strategyFactory, FormFactoryInterface $formFactory, EventDispatcherInterface $eventDispatcher, array $configuration)
    {
        $this->storageFactory = $storageFactory;
        $this->strategyFactory = $strategyFactory;
        $this->formFactory = $formFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->configuration = $configuration;
    }


    public function getSubscription($name): Subscription
    {
        $config = $this->configuration[$name];
        $strategy = $this->strategyFactory->create($config['strategy']);
        $storage = $this->storageFactory->create($config['storage']);
        $strategy->setStorage($storage);

        return new Subscription($name, $strategy, $config['model'], $config['form']);
    }

    /**
     * @return Subscription[]
     */
    public function getSubscriptions(): array
    {
        $subscriptions = [];
        foreach ($this->configuration as $item => $value) {
            $subscriptions[] = $this->getSubscription($item);
        }

        return $subscriptions;
    }

    public function createModel($className): SubscriberInterface
    {
        /** @var SubscriberInterface $subscriber */
        $subscriber = new $className();
        $event = new SubscriberEvent(SubscriberEvent::EVENT_CREATE_SUBSCRIBER, $subscriber);
        $this->eventDispatcher->dispatch($event, SubscriberEvent::EVENT_CREATE_SUBSCRIBER);

        return $subscriber;
    }

    public function createForm(Subscription $subscription, ?SubscriberInterface $subscriber, array $options = [])
    {
        $formConfig = $subscription->getFormConfig();
        $options = array_merge(['subscription' => $subscription->getName()], $formConfig['options'], $options);

        return $this->formFactory->create($formConfig['class'], $subscriber, $options);
    }
}
