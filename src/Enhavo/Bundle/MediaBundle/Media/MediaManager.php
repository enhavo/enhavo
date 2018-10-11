<?php
/**
 * MediaManager.php
 *
 * @since 13/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\MediaBundle\Media;

use Doctrine\ORM\EntityManagerInterface;

use Enhavo\Bundle\MediaBundle\Entity\Format;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Provider\ProviderInterface;
use Enhavo\Bundle\MediaBundle\Repository\FileRepository;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;

class MediaManager
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var FormatManager
     */
    private $formatManager;

    /**
     * @var ProviderInterface
     */
    private $fileRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * MediaManager constructor.
     *
     * @param FormatManager $formatManager
     * @param StorageInterface $storage
     * @param FileRepository $fileRepository
     * @param EntityManagerInterface $em
     * @param ProviderInterface $provider
     */
    public function __construct(
        FormatManager $formatManager,
        StorageInterface $storage,
        FileRepository $fileRepository,
        EntityManagerInterface $em,
        ProviderInterface $provider
    ) {
        $this->formatManager = $formatManager;
        $this->storage = $storage;
        $this->fileRepository = $fileRepository;
        $this->em = $em;
        $this->provider = $provider;
    }

    /**
     * @param array $criteria
     * @return FileInterface
     */
    public function findOneBy($criteria)
    {
        /** @var FileInterface $file */
        $file = $this->fileRepository->findOneBy($criteria);
        if($file !== null) {
            $this->storage->applyContent($file);
        }
        return $file;
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @return FileInterface[]
     */
    public function findBy(array $criteria = [], array $orderBy = [], $limit = null, $offset = null)
    {
        /** @var FileInterface[] $files */
        $files = $this->fileRepository->findBy($criteria, $orderBy, $limit, $offset);
        foreach($files as $file) {
            $this->storage->applyContent($file);
        }
        return $files;
    }

    /**
     * @param int $id
     * @return FileInterface
     */
    public function find($id)
    {
        /** @var FileInterface $file */
        $file = $this->fileRepository->find($id);
        if($file !== null) {
            $this->storage->applyContent($file);
        }
        return $file;
    }

    /**
     * Deletes file and associated formats.
     *
     * @param FileInterface $file
     */
    public function deleteFile(FileInterface $file)
    {
        $this->storage->deleteFile($file);
        $this->formatManager->deleteFormats($file);
        $this->em->remove($file);
        $this->em->flush();
    }

    /**
     * Save file to system.
     *
     * @param FileInterface $file
     */
    public function saveFile(FileInterface $file)
    {
        $this->em->persist($file);
        $this->em->flush();

        $this->storage->saveFile($file);
    }

    /**
     * @param FileInterface $file
     * @param string $format
     *
     * @return FormatInterface
     */
    public function getFormat(FileInterface $file, $format)
    {
        return $this->formatManager->getFormat($file, $format);
    }

    public function saveFormat(FormatInterface $format)
    {
        $this->formatManager->saveFormat($format);
    }

    public function deleteFormat(Format $format)
    {
        $this->formatManager->deleteFormat($format);
    }

    public function collectGarbage()
    {
        $files = $this->fileRepository->findGarbage();
        foreach($files as $file) {
            $this->em->remove($file);
        }
        $this->em->flush();

        foreach($files as $file) {
            $this->storage->deleteFile($file);
            $this->formatManager->deleteFormats($file);
        }
    }

    public function updateFile(FileInterface $file)
    {
        $this->provider->updateFile($file);
    }
}