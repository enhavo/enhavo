<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.11.17
 * Time: 12:09
 */

namespace Enhavo\Bundle\MediaBundle\Filter\Filter;

use Enhavo\Bundle\MediaBundle\Filter\AbstractFilter;
use Enhavo\Bundle\MediaBundle\Model\FilterSetting;
use Imagine\Exception\RuntimeException;
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
        $imagine->save($content->getFilePath(), array('format' => $setting->getSetting('format')));
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
