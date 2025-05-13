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
use Enhavo\Bundle\MediaBundle\Media\FormatManager;
use Enhavo\Bundle\MediaBundle\Model\FilterSetting;

class ParentFilter extends AbstractFilter
{
    /**
     * @var FormatManager
     */
    private $formatManager;

    /**
     * ParentFilter constructor.
     */
    public function __construct(FormatManager $formatManager)
    {
        $this->formatManager = $formatManager;
    }

    public function apply($file, FilterSetting $setting)
    {
        $content = $this->getContent($file);
        $parent = $setting->getSetting('parent');
        $exclude = $setting->getSetting('exclude', []);
        $settings = $this->formatManager->getFormatSettings($parent);
        foreach ($settings as $setting) {
            if (in_array($setting->getType(), $exclude)) {
                continue;
            }
            $filter = $this->formatManager->getFilter($setting->getType());
            $filter->apply($content, $setting);
        }
    }

    public function getType()
    {
        return 'parent';
    }
}
