<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Filter;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FilterSetting;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;

interface FilterInterface extends TypeInterface
{
    /**
     * @param FileInterface|ContentInterface|FormatInterface $file
     *
     * @return void
     */
    public function apply($file, FilterSetting $setting);

    /**
     * Predicts the file extension of the resulting file after running this filter.
     * This function needs to run fast without any actual conversions happening. If the extension cannot be predicted
     * without any slow code, return null.
     *
     * @param string|null $originalExtension Extension predicted by the previous filter or the original file
     * @param FilterSetting $setting
     * @return string|null Predicted extension or null if unpredictable
     */
    public function predictExtension(?string $originalExtension, FilterSetting $setting): ?string;
}
