<?php
/**
 * SubscriberManager.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Subscription;

use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;

class SubscriptionManager
{
    const DEFAULT_PROVIDER = 'local';

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /** @var FactoryInterface */
    private $storageFactory;

    /** @var FactoryInterface */
    private $strategyFactory;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var array */
    private $configuration;

    /**
     * SubscriptionManager constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param FactoryInterface $storageFactory
     * @param FactoryInterface $strategyFactory
     * @param FormFactoryInterface $formFactory
     * @param array $configuration
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, FactoryInterface $storageFactory, FactoryInterface $strategyFactory, FormFactoryInterface $formFactory, array $configuration)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->storageFactory = $storageFactory;
        $this->strategyFactory = $strategyFactory;
        $this->formFactory = $formFactory;
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

    public function createModel($className): SubscriberInterface
    {
        return new $className();
    }

    public function createForm(Subscription $subscription, ?SubscriberInterface $subscriber)
    {
        $formConfig = $subscription->getFormConfig();
        return $this->formFactory->create($formConfig['class'], $subscriber);
    }

    /**
     * @param array|null $groups
     * @return Group[]
     */
    public function resolveGroups(?array $groups): array
    {
        return [];
    }
}
