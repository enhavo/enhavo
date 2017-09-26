<?php
/**
 * FormatManager.php
 *
 * @since 30/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Media;

use Enhavo\Bundle\MediaBundle\Factory\FormatFactory;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatSetting;
use Enhavo\Bundle\MediaBundle\Provider\ProviderInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;
use Imagine\Exception\RuntimeException;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class FormatManager
{
    /**
     * @var array
     */
    private $formats;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * @var FormatFactory
     */
    private $formatFactory;

    public function __construct($formats, StorageInterface $storage, ProviderInterface $provider, FormatFactory $formatFactory)
    {
        $this->formats = $formats;
        $this->storage = $storage;
        $this->provider = $provider;
        $this->formatFactory = $formatFactory;
    }

    public function getFormat(FileInterface $file, $format, $parameters = [])
    {
        $setting = $this->getFormatSettings($format);
        $content = $this->storage->getContent($file);

        $savePath = sprintf('%s.%s', tempnam(sys_get_temp_dir(), 'Imagine'), $file->getExtension());

        try {
            $imagine = new Imagine();
            $imagine = $imagine->load($content->getContent());
            $imagine = $this->format($imagine, $setting);
            $imagine->save($savePath);
        } catch (RuntimeException $e) {
            // if image cant be created we make an empty file
            file_put_contents($savePath, '');
        }

        $formatEntity = $this->formatFactory->createFromPath($savePath);
        $formatEntity->setFile($file);
        $formatEntity->setName($format);
        $this->provider->saveFormat($formatEntity);
        $this->storage->saveFile($formatEntity);
        return $formatEntity;
    }

    public function deleteFormats(FileInterface $file)
    {
        $formats = $this->provider->findAllFormats($file);
        foreach($formats as $format) {
            $this->deleteFormat($format);
        }
    }

    public function saveFormat(FormatInterface $format)
    {
        $this->provider->saveFormat($format);
        $this->storage->saveFile($format);
    }

    public function deleteFormat(FormatInterface $format)
    {
        $this->provider->deleteFormat($format);
        $this->storage->deleteFile($format);
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

    /**
     * @param $format
     * @return FormatSetting
     * @throws \Exception
     */
    private function getFormatSettings($format)
    {
        if(!is_array($this->formats) || !isset($this->formats[$format])) {
            throw new \Exception(sprintf('format "%s" not available', $format));
        }

        $setting = new FormatSetting();
        $setting->setHeight($this->formats[$format]['height']);
        $setting->setWidth($this->formats[$format]['width']);
        $setting->setMaxHeight($this->formats[$format]['maxHeight']);
        $setting->setMaxWidth($this->formats[$format]['maxWidth']);

        return $setting;
    }
}