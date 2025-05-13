<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Filter\Filter;

use Enhavo\Bundle\MediaBundle\Filter\AbstractFilter;
use Enhavo\Bundle\MediaBundle\Model\FilterSetting;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageZoomFilter extends AbstractFilter
{
    public function apply($file, FilterSetting $setting)
    {
        $content = $this->getContent($file);

        $imagine = new Imagine();
        $imagine = $imagine->load($content->getContent());
        $factor = $setting->getSetting('zoom', 1);

        $size = $imagine->getSize();
        $height = intval(round($size->getHeight() * $factor));
        $width = intval(round($size->getWidth() * $factor));

        $imagine->resize(new Box($width, $height));

        $imagine->save($content->getFilePath(), [
            'format' => $this->getImageFormat($content),
        ]);
    }

    public function getType()
    {
        return 'image_zoom';
    }
}
