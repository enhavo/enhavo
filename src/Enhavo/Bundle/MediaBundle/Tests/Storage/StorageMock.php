<?php

namespace Enhavo\Bundle\MediaBundle\Tests\Storage;

use Enhavo\Bundle\MediaBundle\Content\Content;
use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Content\PathContent;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;
use Symfony\Component\Filesystem\Filesystem;

class StorageMock implements StorageInterface
{
    private Filesystem $fs;

    public function __construct(
        private readonly string $directory,
    ) {
        $this->fs = new Filesystem();
    }

    public function deleteContent(FormatInterface|FileInterface $file): void
    {
        $this->fs->remove($this->getPath($file));
    }

    public function saveContent(FormatInterface|FileInterface $file): ContentInterface
    {
        return new Content($file->getContent()->getContent(), $this->getPath($file));
    }

    public function getContent(FormatInterface|FileInterface $file): ContentInterface
    {
        return new PathContent($this->getPath($file));
    }

    protected function getPath(FormatInterface|FileInterface $file): string
    {
        return $this->directory.'/'.$file->getBasename();
    }
}
