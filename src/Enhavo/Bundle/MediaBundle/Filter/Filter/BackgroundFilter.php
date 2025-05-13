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
use Imagine\Image\ImageInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;

class BackgroundFilter extends AbstractFilter
{
    public function apply($file, FilterSetting $setting)
    {
        $content = $this->getContent($file);

        $imagine = new Imagine();
        $imagine = $imagine->load($content->getContent());
        $imagine = $this->format($imagine, $setting);
        $imagine->save($content->getFilePath(), ['format' => $setting->getSetting('format')]);
    }

    public function format(ImageInterface $image, FilterSetting $setting)
    {
        $palette = new RGB();
        $backgroundColor = $palette->color($setting->getSetting('color', '#FFFFFF'), $setting->getSetting('alpha', 100));
        $imagine = new Imagine();
        $newImage = $imagine->create(new Box($image->getSize()->getWidth(), $image->getSize()->getHeight()), $backgroundColor);
        $newImage->paste($image, new Point(0, 0));

        return $newImage;
    }

    public function getType()
    {
        return 'background';
    }
}
