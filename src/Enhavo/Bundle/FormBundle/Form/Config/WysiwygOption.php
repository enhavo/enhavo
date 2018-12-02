<?php
/**
 * WysiwygOption.php
 *
 * @since 07/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\FormBundle\Form\Config;

use Enhavo\Bundle\AppBundle\Util\Assetic;

class WysiwygOption
{
    private $formats;

    private $height;

    private $toolbar1;

    private $toolbar2;

    private $contentCss;

    /**
     * Return all formats. If formats parameter is passed, only this formats will be returned.
     * The format parameter should be an array with titles.
     *
     * @param array $formats Filter formats
     * @return mixed
     */
    public function getFormats($formats = [])
    {
        if(is_array($formats) && count($formats) && is_array($this->formats) && count($this->formats)) {
            $data = array();
            foreach($this->formats as $filterFormat) {
                foreach($formats as $format) {
                    if($filterFormat == $format['title']) {
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
     *
     * @param int $height Set default height if its not set before
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
     * @param int $toolbar1 Set default toolbar1 if its not set before
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
     * @param int $toolbar2 Set default toolbar2 if its not set before
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
     * @param int $contentCss Set default contentCss if its not set before
     * @return mixed
     */
    public function getContentCss($contentCss = null)
    {

        $css = array('@EnhavoFormBundle/Resources/public/css/editor.css');

        if(is_string($this->contentCss)) {
            $css[] = $this->contentCss;
        } elseif(is_array($this->contentCss)) {
            $css = array_merge($css,$this->contentCss);
        } elseif(is_string($contentCss)) {
            $css[] = $contentCss;
        } elseif(is_array($contentCss)) {
            $css = array_merge($css,$contentCss);
        }

        foreach($css as &$path) {
            $path = Assetic::convertPathToAsset($path);
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