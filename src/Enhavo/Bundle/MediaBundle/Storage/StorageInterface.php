<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.08.17
 * Time: 14:58
 */

namespace Enhavo\Bundle\MediaBundle\Storage;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;

interface StorageInterface
{
    public function deleteContent(FileInterface|FormatInterface $file): void;

    public function saveContent(FileInterface|FormatInterface $file): ContentInterface;

    public function getContent(FileInterface|FormatInterface $file): ContentInterface;
}
