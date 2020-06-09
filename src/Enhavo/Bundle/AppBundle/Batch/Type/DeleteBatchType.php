<?php

namespace Enhavo\Bundle\AppBundle\Batch\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * DeleteType.php
 *
 * @since 04/07/16
 * @author gseidel
 */
class DeleteBatchType extends AbstractBatchType
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * DeleteType constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param EntityManagerInterface $em
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, EntityManagerInterface $em)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->em = $em;
    }

    /**
     * @inheritdoc
     */
    public function execute(array $options, array $resources, ResourceInterface $resource = null)
    {
        foreach($resources as $resource) {
            $this->eventDispatcher->dispatch(sprintf('enhavo_app.pre_%s', ResourceActions::DELETE), new ResourceControllerEvent($resource));
            $this->em->remove($resource);
            $this->eventDispatcher->dispatch(sprintf('enhavo_app.post_%s', ResourceActions::DELETE), new ResourceControllerEvent($resource));
        }
        $this->em->flush();
    }

    /**
     * @inheritdoc
     */
    public function createViewData(array $options, ViewData $data, ResourceInterface $resource = null)
    {
        $data['route'] = $options['route'];
        $data['routeParameters'] = $options['route_parameters'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'batch.delete.label',
            'confirm_message' => 'batch.delete.message.confirm',
            'translation_domain' => 'EnhavoAppBundle',
            'route' => null,
            'route_parameters' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'delete';
    }
}
