<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\EventListener;

use Doctrine\ORM\Event\PreRemoveEventArgs;
use Enhavo\Bundle\DoctrineExtensionBundle\Util\AssociationFinder;
use Enhavo\Bundle\MediaBundle\Entity\Format;
use Enhavo\Bundle\MediaBundle\GarbageCollection\GarbageCollector;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\Event\ResourcePostDeleteEvent;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;

class GarbageCollectionSubscriber
{
    private array $filesToScan = [];

    public function __construct(
        private GarbageCollector $garbageCollector,
        private AssociationFinder $associationFinder,
        private EntityRepository $fileRepository,
        protected bool $enableGarbageCollectionListener,
    ) {
    }

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

        // There was an error with entities deleted over database hook on-delete="CASCADE"
        // This line was added to fix that error
        // But then this line caused another error when using batch delete
        // And the original error couldn't be reproduced, so we just removed the line
        // Keeping this line commented in case the original error happens again, though it should probably be fixed in another way
        //        $this->entityManager->clear();

        foreach ($this->filesToScan as $file) {
            // Fix error where files had id = null, might be related to the error commented about above
            if (null === $file->getId()) {
                continue;
            }
            $file = $this->refreshFile($file);
            $this->garbageCollector->runOnFile($file);
        }
        $this->filesToScan = [];
    }

    private function collectFilesAssociatedWithEntity($entity)
    {
        $associations = $this->associationFinder->getOutgoingAssociationMap(get_class($entity));
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($associations as $association) {
            if (is_a($association->getClass(), FileInterface::class, true)) {
                $fieldValue = $propertyAccessor->getValue($entity, $association->getField());
                if (null !== $fieldValue) {
                    if ($association->isSingleValued()) {
                        $this->filesToScan[] = $fieldValue;
                    } else {
                        foreach ($fieldValue as $item) {
                            $this->filesToScan[] = $item;
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
