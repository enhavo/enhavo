<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.11.17
 * Time: 12:11
 */

namespace Enhavo\Bundle\MediaBundle\Filter;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FilterSetting;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;

interface FilterInterface extends TypeInterface
{
    /**
     * @param FileInterface|ContentInterface|FormatInterface $file
     * @param FilterSetting $setting
     * @return void
     */
    public function apply($file, FilterSetting $setting);
}