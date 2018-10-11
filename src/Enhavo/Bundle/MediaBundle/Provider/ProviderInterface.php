<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.08.17
 * Time: 20:52
 */

namespace Enhavo\Bundle\MediaBundle\Provider;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;

interface ProviderInterface
{
    /**
     * @param FileInterface $file
     * @return void
     */
    public function updateFile(FileInterface $file);

    /**
     * @param FormatInterface $format
     * @return void
     */
    public function updateFormat(FormatInterface $format);

    /**
     * @param $object
     * @return bool
     */
    public function supportsClass($object);
}