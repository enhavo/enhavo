<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Model;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;

interface LibraryFileInterface extends FileInterface
{
    public function getItem(): ?ItemInterface;

    public function setItem(?ItemInterface $item): void;
}
