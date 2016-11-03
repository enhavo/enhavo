<?php
/**
 * DoctrineSubscriber.php
 *
 * @since 05/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Enhavo\Bundle\GridBundle\Item\ItemTypeResolver;
use Enhavo\Bundle\GridBundle\Exception\NoTypeFoundException;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

class DoctrineSubscriber implements EventSubscriber
{
    /**
     * @var ItemTypeResolver
     */
    protected $resolver;

    /**
     * DoctrineSubscriber constructor.
     *
     * @param ItemTypeResolver $resolver
     */
    public function __construct(ItemTypeResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postLoad',
            'preRemove',
            'loadClassMetadata'
        );
    }

    /**
     * Store metadata for item type to item relationship
     *
     * @param LifecycleEventArgs $args
     * @throws NoTypeFoundException
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $manager = $args->getEntityManager();
        if ($entity instanceof Item) {
            $itemType = $entity->getItemType();
            $manager->persist($itemType);
            //if itemType id was not set yet, force setting the id
            if($itemType->getId() === null) {
                $manager->flush();
            }
            $entity->setType($this->resolver->getType($itemType));
            $entity->setItemTypeId($itemType->getId());
            $manager->flush();
        }
    }

    /**
     * Load item type into item
     *
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Item) {
            try {
                $repository = $this->resolver->getRepository($entity->getType());
                $itemType = $repository->find($entity->getItemTypeId());
                $entity->setItemType($itemType);
            } catch(NoTypeFoundException $e ) {
                //@TODO: log something
            }
        }
    }

    /**
     * Remove item type if item will be removed
     *
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Item) {
            $itemType = $entity->getItemType();
            $args->getEntityManager()->remove($itemType);
        }
    }

    /**
     * Extend item type if model class differs from parent class
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();
        try {
            $type = $this->resolver->getTypeByParent($metadata->getName());
            $definition = $this->resolver->getDefinition($type);
            if($definition->getParent() !== null && $definition->getModel() !== $definition->getParent()) {
                $metadata->setInheritanceType(ClassMetadata::INHERITANCE_TYPE_SINGLE_TABLE);
                $metadata->setDiscriminatorColumn([
                    'name' => 'discr',
                    'type' => 'string',
                    'length' => 6
                ]);

                $metadata->addDiscriminatorMapClass('target', $definition->getParent());
                $metadata->addDiscriminatorMapClass('extend', $definition->getModel());
            }
        } catch(NoTypeFoundException $e ) {
            // no match type, so nothing to do here
        }
    }
}
