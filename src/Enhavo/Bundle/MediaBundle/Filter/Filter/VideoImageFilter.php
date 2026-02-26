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

use Enhavo\Bundle\MediaBundle\Filter\AbstractFilter;
use Enhavo\Bundle\MediaBundle\Model\FilterSetting;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;

class VideoImageFilter extends AbstractFilter
{
    public function apply($file, FilterSetting $setting)
    {
        $content = $this->getContent($file);

        $converterSettings = $this->container->getParameter('enhavo_media.filter.video_image');

        $converter = FFMpeg::create([
            'ffmpeg.binaries' => $converterSettings['ffmpeg_path'],
            'ffprobe.binaries' => $converterSettings['ffprobe_path'],
            'timeout' => $converterSettings['timeout'],
            'ffmpeg.threads' => $converterSettings['ffmpeg_threads'],
        ]);

        $video = $converter->open($content->getFilePath());

        $temporaryFileName = tempnam('/tmp', 'videoImage');

        $video
            ->frame(TimeCode::fromSeconds(2))
            ->save($temporaryFileName);

        copy($temporaryFileName, $content->getFilePath());
        unlink($temporaryFileName);

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
        return 'video_image';
    }
}
