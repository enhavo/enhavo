<?php
/**
 * FormatManager.php
 *
 * @since 30/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Media;

use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\MediaBundle\Model\FormatSetting;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

class FormatManager
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array
     */
    private $formats;

    public function __construct($path, $filesystem, $formats)
    {
        $this->path = $path;
        $this->filesystem = $filesystem;
        $this->formats = $formats;
    }

    public function createFormat(File $file, $format)
    {
        $setting = $this->getFormatSettings($format);

        $originalPath = sprintf('%s/%s', $this->path, $file->getId());
        $targetPath = sprintf('%s/custom/%s/%s', $this->path, $format, $file->getId());
        $savePath = sprintf('%s.%s', tempnam(sys_get_temp_dir(), 'Imagine'), $file->getExtension());

        $imagine = new Imagine();
        $imagine = $imagine->open($originalPath);
        $imagine = $this->format($imagine, $setting);
        $imagine->save($savePath);

        $this->filesystem->copy($savePath, $targetPath);
    }

    public function format(ImageInterface $image, FormatSetting $setting)
    {
        if($setting->getMaxHeight() && $setting->getMaxWidth() == null) {
            $image = $this->resizeWithMaxHeight($image, $setting->getMaxHeight());
        }

        if($setting->getMaxWidth() && $setting->getMaxHeight() == null) {
            $image = $this->resizeWithMaxWidth($image, $setting->getMaxWidth());
        }

        if($setting->getMaxHeight() && $setting->getMaxWidth()) {
            $image = $this->resizeWithMaxHeightAndWidth($image, $setting->getMaxWidth(), $setting->getMaxHeight());
        }

        if($setting->getHeight() && $setting->getWidth() == null) {
            $image = $this->scaleToHeight($image, $setting->getHeight());
        }

        if($setting->getWidth() && $setting->getHeight() == null) {
            $image = $this->scaleToWidth($image, $setting->getWidth());
        }

        if($setting->getHeight() && $setting->getWidth()) {
            $image = $this->resizeWithFixHeightAndWidth($image, $setting->getWidth(), $setting->getHeight());
        }

        return $image;
    }

    protected function resizeWithFixHeightAndWidth(ImageInterface $image, $width, $height)
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

    protected function resizeWithMaxHeightAndWidth(ImageInterface $image, $width, $height)
    {
        if($image->getSize()->getHeight() > $height) {
            $image = $this->scaleToHeight($image, $height);
        }

        if($image->getSize()->getWidth() > $width) {
            $image = $this->scaleToWidth($image, $width);
        }

        return $image;
    }

    protected function resizeWithMaxHeight(ImageInterface $image, $height)
    {
        if($image->getSize()->getHeight() > $height) {
            $image = $this->scaleToHeight($image, $height);
        }

        return $image;
    }

    protected function resizeWithMaxWidth(ImageInterface $image, $width)
    {
        if($image->getSize()->getWidth() > $width) {
            $image = $this->scaleToWidth($image, $width);
        }

        return $image;
    }

    protected function scaleToHeight(ImageInterface $image, $height)
    {
        $width = $image->getSize()->getWidth() / $image->getSize()->getHeight() * $height;
        return $image->resize(new Box($width, $height));
    }

    protected function scaleToWidth(ImageInterface $image, $width)
    {
        $height = $width / ($image->getSize()->getWidth() / $image->getSize()->getHeight());
        return $image->resize(new Box($width, $height));
    }

    /**
     * @param $format
     * @return FormatSetting
     * @throws \Exception
     */
    private function getFormatSettings($format)
    {
        if(!is_array($this->formats) || !isset($this->formats[$format])) {
            throw new \Exception('format not available');
        }

        $setting = new FormatSetting();
        $setting->setHeight($this->formats[$format]['height']);
        $setting->setWidth($this->formats[$format]['width']);
        $setting->setMaxHeight($this->formats[$format]['maxHeight']);
        $setting->setMaxWidth($this->formats[$format]['maxWidth']);

        return $setting;
    }
}