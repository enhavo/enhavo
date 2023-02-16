<?php

namespace Enhavo\Bundle\MediaBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Enhavo\Bundle\AppBundle\Event\ResourcePostDeleteEvent;
use Enhavo\Bundle\AppBundle\Repository\EntityRepository;
use Enhavo\Bundle\DoctrineExtensionBundle\Util\AssociationFinder;
use Enhavo\Bundle\MediaBundle\Entity\Format;
use Enhavo\Bundle\MediaBundle\GarbageCollection\GarbageCollector;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class GarbageCollectionSubscriber
{
    private array $filesToScan = [];

    public function __construct(
        private GarbageCollector $garbageCollector,
        private AssociationFinder $associationFinder,
        private EntityManagerInterface $entityManager,
        private EntityRepository $fileRepository,
        protected bool $enableGarbageCollectionListener,
    ) {}

    public function preRemove(PreRemoveEventArgs $event): void
    {
        if (!$this->enableGarbageCollectionListener) {
            return;
        }

        $entity = $event->getObject();
        if ($entity instanceof Format) {
            return;
        }
        $this->collectFilesAssociatedWithEntity($entity);
    }

    public function postDelete(ResourcePostDeleteEvent $event): void
    {
        if (!$this->enableGarbageCollectionListener) {
            return;
        }

        // This is required to prevent an error with entities deleted over database hook on-delete="CASCADE"
        $this->entityManager->clear();

        foreach ($this->filesToScan as $file) {
            $file = $this->refreshFile($file);
            $this->garbageCollector->runOnFile($file);
        }
    }

    private function collectFilesAssociatedWithEntity($entity)
    {
        $associations = $this->associationFinder->getOutgoingAssociationMap(get_class($entity));
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach($associations as $association) {
            if (is_a($association->getClass(), FileInterface::class, true)) {
                    $fieldValue = $propertyAccessor->getValue($entity, $association->getField());
                    if ($fieldValue !== null) {
                        if ($association->isSingleValued()) {
                            $this->filesToScan []= $fieldValue;
                        } else {
                            foreach($fieldValue as $item) {
                                $this->filesToScan []= $item;
                            }
                        }
                    }
            }
        }
    }

    private function refreshFile(FileInterface $file)
    {
        return $this->fileRepository->find($file->getId());
    }
}
