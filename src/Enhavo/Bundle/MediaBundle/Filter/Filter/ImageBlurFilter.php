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

class ImageBlurFilter extends AbstractFilter
{
    public function apply($file, FilterSetting $setting)
    {
        $content = $this->getContent($file);

        if (class_exists('\\Imagick')) {
            $image = new \Imagick($content->getFilePath());
            $image->blurImage($setting->getSetting('radius', 5), $setting->getSetting('sigma', 3));
            file_put_contents($content->getFilePath(), $image);
        } else {
            $imagine = new Imagine();
            $imagine = $imagine->load($content->getContent());
            $imagine->effects()->blur($setting->getSetting('sigma', 3));
            $imagine->save($content->getFilePath(), [
                'format' => $this->getImageFormat($content),
            ]);
        }
    }

    public function getType()
    {
        return 'image_blur';
    }
}
