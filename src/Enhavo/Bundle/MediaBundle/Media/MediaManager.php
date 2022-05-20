<?php
/**
 * MediaManager.php
 *
 * @since 13/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\MediaBundle\Media;

use Doctrine\ORM\EntityManagerInterface;

use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\DoctrineExtensionBundle\Util\AssociationFinder;
use Enhavo\Bundle\MediaBundle\Entity\Format;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Provider\ProviderInterface;
use Enhavo\Bundle\MediaBundle\Repository\FileRepository;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;

class MediaManager
{
    public function __construct(
        private StorageInterface $storage,
        private FormatManager $formatManager,
        private FileRepository $fileRepository,
        private ProviderInterface $provider,
        private AssociationFinder $associationFinder,
        private ResourceManager $resourceManager,
    ) {
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
        $this->resourceManager->delete($file);
    }

    /**
     * Save file to system.
     *
     * @param FileInterface $file
     */
    public function saveFile(FileInterface $file)
    {
        $this->resourceManager->save($file);
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
            $this->deleteFile($file);
        }
    }

    public function updateFile(FileInterface $file)
    {
        $this->provider->updateFile($file);
    }

    /**
     * Finds entities referencing the file.
     *
     * @param FileInterface $file File to find references to
     * @return array Array of entities that reference the file
     */
    public function findReferencesTo(FileInterface $file)
    {
        return $this->associationFinder->findAssociationsTo($file, FileInterface::class, [Format::class]);
    }
}
