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

class ImageBlurFilter extends AbstractFilter
{
    public function apply($file, FilterSetting $setting)
    {
        $content = $this->getContent($file);
        try {
            if(class_exists('\\Imagick')) {
                $image = new \Imagick($content->getFilePath());
                $image->blurImage($setting->getSetting('radius', 5), $setting->getSetting('sigma', 3));
                file_put_contents($content->getFilePath(), $image);
            } else {
                $imagine = new Imagine();
                $imagine = $imagine->load($content->getContent());
                $imagine->effects()->blur($setting->getSetting('sigma', 3));
                $imagine->save($content->getFilePath(), [
                    'format' => $this->getImageFormat($content)
                ]);
            }
        } catch (RuntimeException $e) {
            // if image cant be created we make an empty file
            file_put_contents($content->getFilePath(), '');
        }
    }

    public function getType()
    {
        return 'image_blur';
    }
}
