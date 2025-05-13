<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Media;

use Enhavo\Bundle\MediaBundle\Model\FormatInterface;

class ImageCropperManager
{
    public function __construct(
        private readonly array $formats,
    ) {
    }

    public function getFormatRatio(string|FormatInterface $format): ?float
    {
        if ($format instanceof FormatInterface) {
            $name = $format->getName();
        } else {
            $name = $format;
        }

        return $this->findFormatRatio($name);
    }

    private function findFormatRatio(string $formatName): ?float
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

        if (null !== $width && null !== $height) {
            return $width / $height;
        }

        return null;
    }
}
