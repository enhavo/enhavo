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

class PDFImageFilter extends AbstractFilter
{
    const FILTER_SETTING_GHOSTSCRIPT = 'ghostscript';
    const FILTER_SETTING_IMAGICK = 'imagick';

    /**
     * @param ContentInterface|FileInterface|FormatInterface $file
     * @param FilterSetting $setting
     * @throws FilterException
     */
    public function apply($file, FilterSetting $setting)
    {
        $content = $this->getContent($file);

        if ($setting->getSetting('method') === self::FILTER_SETTING_GHOSTSCRIPT) {
            $temporaryFileName = tempnam('/tmp', 'pdfImage');

            $command = explode(' ', 'gs -dSAFER -dBATCH -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -r72 -sOutputFile=' . $temporaryFileName . ' ' . $content->getFilePath());
            $process = new Process($command);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new FilterException('PDFImageFilter with method "' . self::FILTER_SETTING_GHOSTSCRIPT . '" failed. Possible reasons could be broken pdf format or missing Ghostscript executable.');
            }

            copy($temporaryFileName, $content->getFilePath());
            unlink($temporaryFileName);
        } else {
            if(!class_exists("\Imagick")) {
                throw new FilterException('To use the PDFImageFilter with method "' . self::FILTER_SETTING_IMAGICK . '" (default) you need to install Imagick extension for php.');
            }

            $imagick = new \Imagick($content->getFilePath().'[0]');
            $imagick->setImageFormat('jpg');
            file_put_contents($content->getFilePath(), $imagick);
        }

        $this->setExtension($file, 'jpg');
        $this->setMimeType($file, 'image/jpeg');
    }

    public function getType()
    {
        return 'pdf_image';
    }
}
