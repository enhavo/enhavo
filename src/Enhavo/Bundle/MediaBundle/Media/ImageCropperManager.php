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
    /**
     * @var array
     */
    private $formats;

    public function __construct($formats)
    {
        $this->formats = $formats;
    }

    /**
     * @param string|FormatInterface $format
     * @return float|null
     */
    public function getFormatRatio($format)
    {
        $name = null;
        if($format instanceof FormatInterface) {
            $name = $format->getName();
        } else {
            $name = $format;
        }
        return $this->findFormatRatio($name);
    }

    private function findFormatRatio($name)
    {
        $width = null;
        $height = null;

        if(isset($this->formats[$name])) {
            $format = $this->formats[$name];
            if(isset($format[0])) {
                foreach($format as $filter) {
                    if(isset($filter['width'])) {
                        $width = $filter['width'];
                    }
                    if(isset($filter['height'])) {
                        $height = $filter['height'];
                    }
                }
            } else {
                if(isset($format['width'])) {
                    $width = $format['width'];
                }
                if(isset($format['height'])) {
                    $height = $format['height'];
                }
            }
        }

        if($width !== null && $height !== null) {
            return $width / $height;
        }
        return null;
    }
}