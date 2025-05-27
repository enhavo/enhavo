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
use Symfony\Component\Process\Process;

class PDFImageFilter extends AbstractFilter
{
    public const FILTER_SETTING_GHOSTSCRIPT = 'ghostscript';
    public const FILTER_SETTING_IMAGICK = 'imagick';

    /**
     * @param ContentInterface|FileInterface|FormatInterface $file
     *
     * @throws FilterException
     */
    public function apply($file, FilterSetting $setting)
    {
        $content = $this->getContent($file);

        if (self::FILTER_SETTING_GHOSTSCRIPT === $setting->getSetting('method')) {
            $temporaryFileName = tempnam('/tmp', 'pdfImage');

            $command = explode(' ', 'gs -dSAFER -dBATCH -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -r72 -sOutputFile='.$temporaryFileName.' '.$content->getFilePath());
            $process = new Process($command);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new FilterException('PDFImageFilter with method "'.self::FILTER_SETTING_GHOSTSCRIPT.'" failed. Possible reasons could be broken pdf format or missing Ghostscript executable.');
            }

            copy($temporaryFileName, $content->getFilePath());
            unlink($temporaryFileName);
        } else {
            if (!class_exists("\Imagick")) {
                throw new FilterException('To use the PDFImageFilter with method "'.self::FILTER_SETTING_IMAGICK.'" (default) you need to install Imagick extension for php.');
            }

            $imagick = new \Imagick($content->getFilePath().'[0]');
            $imagick->setImageFormat('jpg');
            file_put_contents($content->getFilePath(), $imagick);
        }

        $this->setExtension($file, 'jpg');
        $this->setMimeType($file, 'image/jpeg');
    }

    /**
     * @inheritDoc
     */
    public function predictExtension(?string $originalExtension, FilterSetting $setting): ?string
    {
        return 'jpg';
    }

    public function getType()
    {
        return 'pdf_image';
    }
}
