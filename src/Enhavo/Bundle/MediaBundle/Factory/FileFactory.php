<?php
namespace Enhavo\Bundle\MediaBundle\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Sylius\Component\Resource\Factory\Factory;

class FileFactory extends Factory
{
    /**
     * @var string
     */
    protected $mediaPath;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct($className, $mediaPath, EntityManagerInterface $entityManager)
    {
        parent::__construct($className);

        $this->mediaPath = $mediaPath;
        $this->entityManager = $entityManager;
    }

    /**
     * @param File|null $originalResource
     * @return File
     */
    public function duplicate($originalResource)
    {
        if (!$originalResource) {
            return null;
        }

        /** @var File $newFile */
        $newFile = $this->createNew();

        $newFile->setMimeType($originalResource->getMimeType());
        $newFile->setExtension($originalResource->getExtension());
        $newFile->setOrder($originalResource->getOrder());
        $newFile->setFilename($originalResource->getFilename());
        $newFile->setSlug($originalResource->getSlug());
        $newFile->setParameters($originalResource->getParameters());
        $newFile->setGarbage(false);
        $newFile->setGarbageTimestamp(new \DateTime());

        $this->entityManager->persist($newFile);
        $this->entityManager->flush();

        copy($this->mediaPath . '/' . $originalResource->getId(), $this->mediaPath . '/' . $newFile->getId());

        return $newFile;
    }
}
