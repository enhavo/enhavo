<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.08.17
 * Time: 14:07
 */

namespace Enhavo\Bundle\MediaBundle\Storage;

use Symfony\Component\Filesystem\Filesystem;
use Enhavo\Bundle\MediaBundle\Content\PathContent;
use Enhavo\Bundle\MediaBundle\Exception\StorageException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;

class LocalFileStorage implements StorageInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct($path, Filesystem $filesystem)
    {
        $this->path = $path;
        $this->filesystem = $filesystem;
    }

    public function deleteFile($file)
    {
        $savePath = $this->getAndCreateSavePath($file);
        $this->filesystem->remove($savePath);
    }

    public function saveFile($file)
    {
        $savePath = $this->getAndCreateSavePath($file);
        $this->filesystem->dumpFile($savePath, $file->getContent()->getContent());
    }

    public function applyContent($file)
    {
        $file->setContent($this->getContent($file));
    }

    public function getContent($file, $format = null)
    {
        $savePath = $this->getAndCreateSavePath($file);
        return new PathContent($savePath);
    }

    private function createPathIfNotExists($path)
    {
        if(!$this->filesystem->exists($path)) {
            $this->filesystem->mkdir($path, 0755);
        }
    }

    private function getAndCreateSavePath($file)
    {
        $savePath = null;
        $this->createPathIfNotExists($this->path);

        if($file instanceof FileInterface) {
            if($file->getId() === null) {
                throw new StorageException(sprintf(
                    'File with name "%s" need id for storing to filesystem. Make sure you saved it before.',
                    $file->getFilename()
                ));
            }

            $savePath = sprintf('%s/%s', $this->path, $file->getId());
        } elseif($file instanceof FormatInterface) {
            $originFile = $file->getFile();
            if($originFile->getId() === null) {
                throw new StorageException(sprintf(
                    'File with name "%s" need id for storing to filesystem. Make sure you saved it before.',
                    $file->getFilename()
                ));
            }

            $savePath = sprintf('%s/%s/%s', $this->path, $file->getName(), $originFile->getId());
            $this->createPathIfNotExists(sprintf('%s/%s', $this->path, $file->getName()));
        }

        return $savePath;
    }
}
