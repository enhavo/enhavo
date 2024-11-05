<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Entity;

use Enhavo\Bundle\MediaLibraryBundle\Model\ItemInterface;
use Enhavo\Bundle\MediaLibraryBundle\Model\LibraryFileInterface;

class File extends \Enhavo\Bundle\MediaBundle\Entity\File implements LibraryFileInterface
{
    private ?ItemInterface $item;

    public function getItem(): ?ItemInterface
    {
        return $this->item;
    }

    public function setItem(?ItemInterface $item): void
    {
        $this->item = $item;
    }
}
