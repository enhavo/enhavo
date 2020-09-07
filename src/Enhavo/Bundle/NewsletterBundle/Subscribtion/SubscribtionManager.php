<?php
/**
 * SubscriberManager.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Subscribtion;

use Enhavo\Bundle\NewsletterBundle\Event\NewsletterEvents;
use Enhavo\Bundle\NewsletterBundle\Event\SubscriberEvent;
use Enhavo\Bundle\NewsletterBundle\Form\Resolver;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SubscribtionManager
{
    const DEFAULT_PROVIDER = 'local';

    /**
     * @var Resolver
     */
    private $formResolver;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /** @var FactoryInterface */
    private $storageFactory;

    /** @var FactoryInterface */
    private $strategyFactory;

    /** @var array */
    private $configuration;

    /**
     * SubscriberManager constructor.
     * @param Resolver $formResolver
     * @param EventDispatcherInterface $eventDispatcher
     * @param FactoryInterface $storageFactory
     * @param FactoryInterface $strategyFactory
     * @param array $configuration
     */
    public function __construct(Resolver $formResolver, EventDispatcherInterface $eventDispatcher, FactoryInterface $storageFactory, FactoryInterface $strategyFactory, array $configuration)
    {
        $this->formResolver = $formResolver;
        $this->eventDispatcher = $eventDispatcher;
        $this->storageFactory = $storageFactory;
        $this->strategyFactory = $strategyFactory;
        $this->configuration = $configuration;
    }

    public function getSubscribtion($name): Subscribtion
    {
        $config = $this->configuration[$name];
        $strategy = $this->strategyFactory->create($config['strategy']);
        $storage = $this->storageFactory->create($config['storage']);
        $strategy->setStorage($storage);


//         blutze: create form? (strategy based email validation)
//        $formType = $this->formResolver->resolveType($config['form']);
//        $form = $this->createForm($formType, $subscriber);

        return new Subscribtion($name, $strategy);
    }
}
