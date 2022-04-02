<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.11.17
 * Time: 16:39
 */

namespace Enhavo\Bundle\MediaBundle\Filter\Filter;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Exception\FilterException;
use Enhavo\Bundle\MediaBundle\Filter\AbstractFilter;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FilterSetting;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Symfony\Component\Process\Process;

class TextImageFilter extends AbstractFilter
{

    /**
     * @param ContentInterface|FileInterface|FormatInterface $file
     * @param FilterSetting $setting
     * @throws FilterException
     */
    public function apply($file, FilterSetting $setting)
    {
        $content = $this->getContent($file);

        if(!class_exists("\Imagick")) {
            throw new FilterException('To use the TextImageFilter you need to install Imagick extension for php.');
        }

        $imagick = new \Imagick();
        $imagick->readImage('text:' . $content->getFilePath() . '[0]');
        $imagick->resizeImage(512, 512, \Imagick::FILTER_LANCZOS, 1);
        $imagick->setImageAlphaChannel(\Imagick::ALPHACHANNEL_DEACTIVATE);
        $imagick->setImageFormat('png');
        $imagick->setImageCompressionQuality(85);
        $imagick->writeImage($content->getFilePath());

        $this->setExtension($file, 'png');
        $this->setMimeType($file, 'image/png');
    }

    public function getType()
    {
        return 'text_image';
    }
}
