<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Model;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;

interface LibraryFileInterface extends FileInterface
{
    public function getItem(): ?ItemInterface;

    public function setItem(?ItemInterface $item): void;
}
