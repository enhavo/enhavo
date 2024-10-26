<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 13.01.18
 * Time: 16:04
 */

namespace Enhavo\Bundle\MediaBundle\Media;

use Enhavo\Bundle\MediaBundle\Model\FormatInterface;

class ImageCropperManager
{
    public function __construct(
        private readonly array $formats
    )
    {
    }

    public function getFormatRatio(string|FormatInterface $format): float|null
    {
        if ($format instanceof FormatInterface) {
            $name = $format->getName();
        } else {
            $name = $format;
        }
        return $this->findFormatRatio($name);
    }

    private function findFormatRatio(string $formatName): float|null
    {
        $width = null;
        $height = null;

        if (isset($this->formats[$formatName])) {
            $format = $this->formats[$formatName];
            if (isset($format[0])) {
                foreach ($format as $filter) {
                    if (isset($filter['width'])) {
                        $width = $filter['width'];
                    }
                    if (isset($filter['height'])) {
                        $height = $filter['height'];
                    }
                }
            } else {
                if (isset($format['width'])) {
                    $width = $format['width'];
                }
                if (isset($format['height'])) {
                    $height = $format['height'];
                }
            }
        }

        if ($width !== null && $height !== null) {
            return $width / $height;
        }
        return null;
    }
}
