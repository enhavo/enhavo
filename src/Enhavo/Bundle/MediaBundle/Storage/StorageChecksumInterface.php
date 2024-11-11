<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.08.17
 * Time: 14:58
 */

namespace Enhavo\Bundle\MediaBundle\Storage;


use Enhavo\Bundle\MediaBundle\Content\ContentInterface;

interface StorageChecksumInterface
{
    public function existsChecksum(string $checksum): bool;

    public function getContentByChecksum(string $checksum): ContentInterface;
}
