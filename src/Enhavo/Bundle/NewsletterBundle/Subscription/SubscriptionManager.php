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
     * @param FactoryInterface $storageFactory
     * @param FactoryInterface $strategyFactory
     * @param FormFactoryInterface $formFactory
     * @param array $configuration
     */
    public function __construct(FactoryInterface $storageFactory, FactoryInterface $strategyFactory, FormFactoryInterface $formFactory, array $configuration)
    {
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
        return new $className();
    }

    public function createForm(Subscription $subscription, ?SubscriberInterface $subscriber, array $options = [])
    {
        $formConfig = $subscription->getFormConfig();
        $options = array_merge($formConfig['options'], $options);
        return $this->formFactory->create($formConfig['class'], $subscriber, $options);
    }

    /**
     * @param array|null $groups
     * @return Group[]
     */
    public function resolveGroups(?array $groups): array
    {
        // blutze: what is it doing?
        return [];
    }
}
