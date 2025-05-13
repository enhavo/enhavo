<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Behat\Context;

use Enhavo\Bundle\AppBundle\Behat\Context\KernelContext;
use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Content\PathContent;
use Enhavo\Bundle\MediaBundle\Filter\FilterInterface;
use Enhavo\Bundle\MediaBundle\Model\FilterSetting;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Palette\RGB;

class MediaContext extends KernelContext
{
    /**
     * @var ContentInterface
     */
    public static $image;

    /**
     * @var FilterSetting
     */
    public static $setting;

    /**
     * @Given A new filter setting
     */
    public function aNewFilterSetting()
    {
        self::$setting = new FilterSetting();
    }

    /**
     * @Given A image with ":width"px width and ":height"px height
     */
    public function aImageWithPxHeightAndPxWidth($width, $height)
    {
        $imagine = new Imagine();

        $palette = new RGB();
        $size = new Box($width, $height);
        $color = $palette->color('#000', 100);
        $image = $imagine->create($size, $color);

        $tempPath = tempnam(sys_get_temp_dir(), 'Content').'.png';
        $image->save($tempPath);
        self::$image = new PathContent($tempPath);
    }

    /**
     * @Given A filter setting ":key" with value ":value"
     */
    public function aFilterSetting($key, $value)
    {
        self::$setting->setSetting($key, $value);
    }

    /**
     * @Then I apply image on filter ":type"
     */
    public function iApplyImageOnFilter($type)
    {
        $filterCollector = $this->get('enhavo_media.filter_collector');
        /** @var FilterInterface $filter */
        $filter = $filterCollector->getType($type);
        $filter->apply(self::$image, self::$setting);
    }

    /**
     * @Then the image size should be ":width"px width and ":height"px height
     */
    public function theImageSizeShouldBePxHeightAndPxWidth($height, $width)
    {
        $imagine = new Imagine();
        $image = $imagine->open(self::$image->getFilePath());

        $box = $image->getSize();

        if ($height != $box->getHeight() || $width != $box->getWidth()) {
            throw new \Exception(sprintf('size is %spx width and %spx height and does not fit', $box->getWidth(), $box->getHeight()));
        }
    }
}
