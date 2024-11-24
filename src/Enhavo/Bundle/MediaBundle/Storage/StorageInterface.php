<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.08.17
 * Time: 14:58
 */

namespace Enhavo\Bundle\MediaBundle\Storage;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Exception\StorageException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;

interface StorageInterface
{
    /** @throws StorageException */
    public function deleteContent(FileInterface|FormatInterface $file): void;

    /** @throws StorageException */
    public function saveContent(FileInterface|FormatInterface $file): ContentInterface;

    /** @throws StorageException */
    public function getContent(FileInterface|FormatInterface $file): ContentInterface;
}
