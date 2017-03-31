<?php
/**
 * MediaContext.php
 *
 * @since 30/03/17
 * @author gseidel
 */

namespace Enhavo\Behat\Context;

use Enhavo\Bundle\MediaBundle\Model\FormatSetting;
use Imagine\Gd\Imagine;
use Imagine\Image\ImageInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Box;

class MediaContext extends KernelContext
{
    /**
     * @var ImageInterface
     */
    public static $image;

    /**
     * @var FormatSetting
     */
    public static $setting;

    /**
     * @Given A image with ":width"px width and ":height"px height
     */
    public function aImageWithPxHeightAndPxWidth($width, $height)
    {
        $imagine = new Imagine();

        $palette = new RGB();
        $size  = new Box($width, $height);
        $color = $palette->color('#000', 100);
        $image = $imagine->create($size, $color);

        self::$image = $image;
    }

    /**
     * @Given A image format setting with ":width"px width and ":height"px height
     */
    public function aImageFormatSettingWithPxHeightAndPxWidth($width, $height)
    {
        $setting = new FormatSetting();
        $setting->setHeight($height);
        $setting->setWidth($width);
        self::$setting = $setting;
    }

    /**
     * @Given A image format setting with ":arg1"px width
     */
    public function aImageFormatSettingWithPxWidth($arg1)
    {
        $setting = new FormatSetting();
        $setting->setWidth($arg1);
        self::$setting = $setting;
    }

    /**
     * @Given A image format setting with ":arg1"px height
     */
    public function aImageFormatSettingWithPxHeight($arg1)
    {
        $setting = new FormatSetting();
        $setting->setHeight($arg1);
        self::$setting = $setting;
    }

    /**
     * @Given A image format setting with ":arg1"px max height
     */
    public function aImageFormatSettingWithPxMaxHeight($arg1)
    {
        $setting = new FormatSetting();
        $setting->setMaxHeight($arg1);
        self::$setting = $setting;
    }

    /**
     * @Given A image format setting with ":arg1"px max width
     */
    public function aImageFormatSettingWithPxMaxWidth($arg1)
    {
        $setting = new FormatSetting();
        $setting->setMaxWidth($arg1);
        self::$setting = $setting;
    }

    /**
     * @Given A image format setting with ":arg1"px max width and ":arg2"px max height
     */
    public function aImageWithPxMaxWidthAndPxMaxHeight($arg1, $arg2)
    {
        $setting = new FormatSetting();
        $setting->setMaxWidth($arg1);
        $setting->setMaxHeight($arg2);
        self::$setting = $setting;
    }

    /**
     * @Then I apply image format
     */
    public function iApplyImageFormat()
    {
        self::$image = $this->get('enhavo_media.format_manager')->format(self::$image, self::$setting);
    }

    /**
     * @Then the image size should be ":width"px width and ":height"px height
     */
    public function theImageSizeShouldBePxHeightAndPxWidth($height, $width)
    {
        $box = self::$image->getSize();

        if($height != $box->getHeight() || $width != $box->getWidth()) {
            throw new \Exception(sprintf(
                'size is %spx width and %spx height and does not fit',
                $box->getWidth(),
                $box->getHeight()));
        }

    }

}