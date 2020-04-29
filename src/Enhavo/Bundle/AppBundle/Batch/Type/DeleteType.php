<?php

namespace Enhavo\Bundle\AppBundle\Batch\Type;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * DeleteType.php
 *
 * @since 04/07/16
 * @author gseidel
 */
class DeleteType extends AbstractBatchType
{

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /**
     * DeleteType constructor.
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     * @inheritdoc
     */
    public function execute(array $options, $resources)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        foreach($resources as $resource) {
            $this->eventDispatcher->dispatch(sprintf('enhavo_app.pre_%s', ResourceActions::DELETE), new ResourceControllerEvent($resource));
            $em->remove($resource);
            $this->eventDispatcher->dispatch(sprintf('enhavo_app.post_%s', ResourceActions::DELETE), new ResourceControllerEvent($resource));
        }
        $em->flush();
    }

    /**
     * @inheritdoc
     */
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);
        $data['route'] = $options['route'];
        $data['routeParameters'] = $options['route_parameters'];
        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'batch.delete.label',
            'confirm_message' => 'batch.delete.message.confirm',
            'translation_domain' => 'EnhavoAppBundle',
            'route' => null,
            'route_parameters' => null,
        ]);
    }

    public function getType()
    {
        return 'delete';
    }
}
