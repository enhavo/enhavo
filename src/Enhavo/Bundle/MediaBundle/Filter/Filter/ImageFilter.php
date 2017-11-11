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

class ImageFilter extends AbstractFilter
{
    public function apply($file, FilterSetting $setting)
    {
        $content = $this->getContent($file);

        try {
            $imagine = new Imagine();
            $imagine = $imagine->load($content->getContent());
            $imagine = $this->format($imagine, $setting);
            $imagine->save($content->getFilePath());
        } catch (RuntimeException $e) {
            // if image cant be created we make an empty file
            file_put_contents($content->getFilePath(), '');
        }
    }

    public function format(ImageInterface $image, FilterSetting $setting)
    {
        if($setting->getSetting('maxHeight') && $setting->getSetting('maxWidth') == null) {
            $image = $this->resizeWithMaxHeight($image, $setting->getSetting('maxHeight'));
        }

        if($setting->getSetting('maxWidth') && $setting->getSetting('maxHeight') == null) {
            $image = $this->resizeWithMaxWidth($image, $setting->getSetting('maxWidth'));
        }

        if($setting->getSetting('maxHeight') && $setting->getSetting('maxWidth')) {
            $image = $this->resizeWithMaxHeightAndWidth($image, $setting->getSetting('maxWidth'), $setting->getSetting('maxHeight'));
        }

        if($setting->getSetting('height') && $setting->getSetting('width') == null) {
            $image = $this->scaleToHeight($image, $setting->getSetting('height'));
        }

        if($setting->getSetting('width') && $setting->getSetting('height') == null) {
            $image = $this->scaleToWidth($image, $setting->getSetting('width'));
        }

        if($setting->getSetting('height') && $setting->getSetting('width')) {
            $image = $this->resizeWithFixHeightAndWidth($image, $setting->getSetting('width'), $setting->getSetting('height'));
        }

        return $image;
    }

    private function resizeWithFixHeightAndWidth(ImageInterface $image, $width, $height)
    {
        if($image->getSize()->getHeight() < $height) {
            $image = $this->scaleToHeight($image, $height);
        }

        if($image->getSize()->getWidth() < $width) {
            $image = $this->scaleToWidth($image, $width);
        }

        $image = $image->thumbnail(new Box($width, $height), ImageInterface::THUMBNAIL_OUTBOUND);

        return $image;
    }

    private function resizeWithMaxHeightAndWidth(ImageInterface $image, $width, $height)
    {
        if($image->getSize()->getHeight() > $height) {
            $image = $this->scaleToHeight($image, $height);
        }

        if($image->getSize()->getWidth() > $width) {
            $image = $this->scaleToWidth($image, $width);
        }

        return $image;
    }

    private function resizeWithMaxHeight(ImageInterface $image, $height)
    {
        if($image->getSize()->getHeight() > $height) {
            $image = $this->scaleToHeight($image, $height);
        }

        return $image;
    }

    private function resizeWithMaxWidth(ImageInterface $image, $width)
    {
        if($image->getSize()->getWidth() > $width) {
            $image = $this->scaleToWidth($image, $width);
        }

        return $image;
    }

    private function scaleToHeight(ImageInterface $image, $height)
    {
        $width = $image->getSize()->getWidth() / $image->getSize()->getHeight() * $height;
        return $image->resize(new Box($width, $height));
    }

    private function scaleToWidth(ImageInterface $image, $width)
    {
        $height = $width / ($image->getSize()->getWidth() / $image->getSize()->getHeight());
        return $image->resize(new Box($width, $height));
    }

    public function getType()
    {
        return 'image';
    }
}