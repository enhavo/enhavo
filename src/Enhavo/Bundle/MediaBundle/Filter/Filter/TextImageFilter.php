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

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Exception\FilterException;
use Enhavo\Bundle\MediaBundle\Filter\AbstractFilter;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FilterSetting;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;

class TextImageFilter extends AbstractFilter
{
    /**
     * @param ContentInterface|FileInterface|FormatInterface $file
     *
     * @throws FilterException
     */
    public function apply($file, FilterSetting $setting)
    {
        $content = $this->getContent($file);

        if (!class_exists("\Imagick")) {
            throw new FilterException('To use the TextImageFilter you need to install Imagick extension for php.');
        }

        $imagick = new \Imagick();
        $imagick->readImage('text:'.$content->getFilePath().'[0]');
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
