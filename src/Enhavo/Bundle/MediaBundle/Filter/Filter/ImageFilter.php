<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.11.17
 * Time: 12:09
 */

namespace Enhavo\Bundle\MediaBundle\Filter\Filter;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Filter\AbstractFilter;
use Enhavo\Bundle\MediaBundle\Model\FilterSetting;
use Imagine\Exception\RuntimeException;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;

class ImageFilter extends AbstractFilter
{
    public function apply($file, FilterSetting $setting)
    {
        $content = $this->getContent($file);

        try {
            $imagine = new Imagine();
            $imagine = $imagine->load($content->getContent());
            $imagine = $this->resize($imagine, $setting);
            $imagine->save($content->getFilePath(), [
                'format' => $this->getFormat($content, $setting)
            ]);
        } catch (RuntimeException $e) {
            // if image cant be created we make an empty file
            file_put_contents($content->getFilePath(), '');
        }
    }

    private function getFormat(ContentInterface $content, FilterSetting $setting)
    {
        $format = $setting->getSetting('format');
        if($format === null) {
            $type = exif_imagetype($content->getFilePath());
            switch ($type) {
                case(IMAGETYPE_GIF):
                    return 'gif';
                case(IMAGETYPE_JPEG):
                    return 'jpg';
                case(IMAGETYPE_PNG):
                    return 'png';
            }
            return 'png';
        }
        return $format;
    }

    private function resize(ImageInterface $image, FilterSetting $setting)
    {
        if($setting->getSetting('cropHeight') !== null &&
            $setting->getSetting('cropWidth') !== null  &&
            $setting->getSetting('cropY') !== null  &&
            $setting->getSetting('cropX') !== null
        ) {
            $image = $this->crop(
                $image,
                $setting->getSetting('cropWidth'),
                $setting->getSetting('cropHeight'),
                $setting->getSetting('cropX'),
                $setting->getSetting('cropY')
            );
        }

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
            $image = $this->resizeWithFixHeightAndWidth(
                $image, $setting->getSetting('width'),
                $setting->getSetting('height'),
                $setting->getSetting('mode', ImageInterface::THUMBNAIL_OUTBOUND)
            );
        }

        return $image;
    }

    private function crop(ImageInterface $image, $width, $height, $x, $y)
    {
        $palette = new RGB();
        $backgroundColor = $palette->color(array(255, 255, 255), 0);
        $imagine = new Imagine();

        $size = $image->getSize();
        // resize image for x position if necessary
        if($x < 0) {
            $newImage = $imagine->create(new Box($size->getWidth() + abs($x), $size->getHeight()), $backgroundColor);
            $image = $newImage->paste($image, new Point(abs($x), 0));
            $x = 0;
        } elseif($x > $size->getWidth()) {
            $newImage = $imagine->create(new Box($x + 1, $size->getHeight()), $backgroundColor);
            $image = $newImage->paste($image, new Point(0, 0));
        }

        $size = $image->getSize();
        // resize image for y position if necessary
        if($y < 0) {
            $newImage = $imagine->create(new Box($size->getWidth(), $size->getHeight() + abs($y)), $backgroundColor);
            $image = $newImage->paste($image, new Point(0, abs($y)));
            $y = 0;
        } elseif($y > $size->getHeight()) {
            $newImage = $imagine->create(new Box($size->getWidth(), $y + 1), $backgroundColor);
            $image = $newImage->paste($image, new Point(0, 0));
        }

        $size = $image->getSize();
        // resize image if width is to big
        if(($x + $width) > $size->getWidth()) {
            $newImage = $imagine->create(new Box($x + $width, $size->getHeight()), $backgroundColor);
            $image = $newImage->paste($image, new Point(0, 0));
        }

        $size = $image->getSize();
        // resize image if height is to big
        if(($y + $height) > $size->getHeight()) {
            $newImage = $imagine->create(new Box($size->getWidth(), $y + $height + 1), $backgroundColor);
            $image = $newImage->paste($image, new Point(0, 0));
        }

        $image = $image->crop(new Point($x, $y), new Box($width, $height));
        return $image;
    }

    private function resizeWithFixHeightAndWidth(ImageInterface $image, $width, $height, $mode)
    {
        if($image->getSize()->getHeight() < $height) {
            $image = $this->scaleToHeight($image, $height);
        }

        if($image->getSize()->getWidth() < $width) {
            $image = $this->scaleToWidth($image, $width);
        }

        $image = $image->thumbnail(new Box($width, $height), $mode);

        if($mode === ImageInterface::THUMBNAIL_OUTBOUND) {
            return $image;
        }

        //inset my not have target size, so we resize
        $palette = new RGB();
        $backgroundColor = $palette->color(array(255, 255, 255), 0);
        $imagine = new Imagine();
        $newImage = $imagine->create(new Box($width, $height), $backgroundColor);
        $newImage->resize(new Box($width, $height));
        
        $size = $image->getSize();
        if($size->getHeight() !== $height) {
            $y =  intval($height / 2) - intval($size->getHeight() / 2);
            $newImage->paste($image, new Point(0, $y));
        } elseif($size->getWidth() !== $width) {
            $x =  intval($width / 2) - intval($size->getWidth() / 2);
            $newImage->paste($image, new Point($x,0));
        } else {
            return $image;
        }

        return $newImage;
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