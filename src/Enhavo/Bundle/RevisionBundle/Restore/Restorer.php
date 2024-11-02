<?php

namespace Enhavo\Bundle\RevisionBundle\Restore;

use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;
use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Component\Type\FactoryInterface;
use Enhavo\Bundle\RevisionBundle\Restore\Metadata\Metadata;

class Restorer
{
    public function __construct(
        private readonly MetadataRepository $metadataRepository,
        private readonly FactoryInterface $restoreFactory,
    )
    {
    }

    public function restore(RevisionInterface $subject, RevisionInterface $revision, $context = []): RevisionInterface
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($subject);

        if ($metadata->getClass()) {
            /** @var Restore $restore */
            $restore = $this->restoreFactory->create($metadata->getClass());
            return $restore->restore($subject, $revision, $context);
        }

        $reflection = new \ReflectionClass($metadata->getClassName());

        foreach ($metadata->getProperties() as $property => $config) {

            $property = $reflection->getProperty($property);
            $property->setAccessible(true);

            $subjectValue = $property->getValue($subject);
            $revisionValue = $property->getValue($revision);

            /** @var Restore $restore */
            $restore = $this->restoreFactory->create($config);
            $newValue = $restore->restore($subjectValue, $revisionValue, $context);
            $property->setValue($subject, $newValue);
        }

        return $subject;
    }
}
