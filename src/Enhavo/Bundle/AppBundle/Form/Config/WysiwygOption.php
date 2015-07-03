<?php
/**
 * WysiwygOption.php
 *
 * @since 07/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Form\Config;

use Enhavo\Bundle\AppBundle\Util\Assetic;

class WysiwygOption
{
    private $formats;

    private $height;

    private $toolbar1;

    private $toolbar2;

    private $contentCss;

    /**
     * @return mixed
     */
    public function getFormats($formats = null)
    {
        if(is_array($formats) && count($formats) && is_array($this->formats) && count($this->formats)) {
            $data = array();
            foreach($formats as $format) {
                foreach($this->formats as $searchFormat) {
                    if(isset($format['title']) && $format['title'] == $searchFormat) {
                        $data[] = $format;
                        break;
                    }
                }
            }
            return $data;
        }
        return $formats;
    }

    /**
     * @param mixed $formats
     */
    public function setFormats($formats)
    {
        $this->formats = $formats;
    }

    /**
     * @return mixed
     */
    public function getHeight($height = null)
    {
        if(!empty($this->height)) {
            return $this->height;
        }
        return $height;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return mixed
     */
    public function getToolbar1($toolbar1 = null)
    {
        if(is_string($this->toolbar1)) {
            return $this->toolbar1;
        }
        return $toolbar1;
    }

    /**
     * @param mixed $toolbar1
     */
    public function setToolbar1($toolbar1)
    {
        $this->toolbar1 = $toolbar1;
    }

    /**
     * @return mixed
     */
    public function getToolbar2($toolbar2 = null)
    {
        if(is_string($this->toolbar2)) {
            return $this->toolbar2;
        }
        return $toolbar2;
    }

    /**
     * @param mixed $toolbar2
     */
    public function setToolbar2($toolbar2)
    {
        $this->toolbar2 = $toolbar2;
    }

    /**
     * @return mixed
     */
    public function getContentCss($contentCss)
    {
        if(is_string($this->contentCss) || is_array($this->contentCss)) {
            $css = $this->contentCss;
        } else {
            $css = $contentCss;
        }

        if(empty($css)) {
            return $css;
        }

        if(is_array($css)) {
            foreach($css as &$path) {
                $path = Assetic::convertPathToAsset($path);
            }
        } else {
            $css = Assetic::convertPathToAsset($css);
        }
        return $css;
    }

    /**
     * @param mixed $contentCss
     */
    public function setContentCss($contentCss)
    {
        $this->contentCss = $contentCss;
    }
}