<?php

namespace Enhavo\Bundle\MediaBundle\Storage;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Routing\UrlGeneratorInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * The SymlinkEnhancedFileStorage acts as a wrapper around another storage service,
 * delegating the actual file storage and retrieval operations to it while ensuring
 * that files are also accessible through a symlink on a public path.
 * This allows direct serving of media files through a web server without accessing php.
 */
class SymlinkEnhancedFileStorage implements StorageInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly string $publicDir,
        private readonly StorageInterface $storage,
        private readonly Filesystem $fs,
    ) {
    }

    public function deleteContent(FormatInterface|FileInterface $file): void
    {
        $path = $this->getSymlinkPath($file);
        if ($this->fs->exists($path)) {
            $this->fs->remove($path);
        }

        $this->storage->deleteContent($file);
    }

    public function saveContent(FormatInterface|FileInterface $file): ContentInterface
    {
        $content = $this->storage->saveContent($file);

        $path = $this->getSymlinkPath($file);

        $dir = dirname($path);
        if (!$this->fs->exists($dir)) {
            $this->fs->mkdir($dir);
        }

        if (!$this->fs->exists($path)) {
            $this->fs->symlink($content->getFilePath(), $path);
        }

        return $content;
    }

    public function getContent(FormatInterface|FileInterface $file): ContentInterface
    {
        return $this->storage->getContent($file);
    }

    private function getSymlinkPath(FormatInterface|FileInterface $file): string
    {
        if ($file instanceof FileInterface) {
            $url = $this->urlGenerator->generate($file);
        } else {
            $url = $this->urlGenerator->generateFormat($file->getFile(), $file->getName());
        }

        return $this->publicDir . $url;
    }
}
