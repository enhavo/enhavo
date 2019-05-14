<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.11.17
 * Time: 12:09
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
     * @param FormatManager $formatManager
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
        foreach($settings as $setting) {
            if(in_array($setting->getType(), $exclude)) {
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
