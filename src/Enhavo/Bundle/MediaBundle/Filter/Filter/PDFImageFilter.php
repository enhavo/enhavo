<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.11.17
 * Time: 16:39
 */

namespace Enhavo\Bundle\MediaBundle\Filter\Filter;

use Enhavo\Bundle\MediaBundle\Exception\FilterException;
use Enhavo\Bundle\MediaBundle\Filter\AbstractFilter;
use Enhavo\Bundle\MediaBundle\Model\FilterSetting;

class PDFImageFilter extends AbstractFilter
{
    public function apply($file, FilterSetting $setting)
    {
        $content = $this->getContent($file);

        if(!class_exists("\Imagick")) {
            throw new FilterException('To use the PDFImageFilter you need to install Imagick extension for php');
        }

        $imagick = new \Imagick($content->getFilePath().'[0]');
        $imagick->setImageFormat('jpg');
        file_put_contents($content->getFilePath(), $imagick);

        $this->setExtension($file, 'jpg');
        $this->setMimeType($file, 'image/jpeg');
    }

    public function getType()
    {
        return 'pdf_image';
    }
}