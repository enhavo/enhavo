<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
