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
    /**
     * @param FileInterface|FormatInterface $file
     * @return string
     */
    public function deleteFile($file);

    /**
     * @param FileInterface|FormatInterface $file
     */
    public function saveFile($file);

    /**
     * @param FileInterface|FormatInterface $file
     */
    public function applyContent($file);

    /**
     * @param FileInterface|FormatInterface $file
     * @return ContentInterface
     */
    public function getContent($file);
}