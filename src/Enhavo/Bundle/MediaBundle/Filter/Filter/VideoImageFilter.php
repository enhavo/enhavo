<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.11.17
 * Time: 16:39
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

    public function getType()
    {
        return 'video_image';
    }
}
