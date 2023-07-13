<?php

namespace Enhavo\Bundle\MediaBundle\Filter\Filter;

use Enhavo\Bundle\MediaBundle\Filter\AbstractFilter;
use Enhavo\Bundle\MediaBundle\Media\FormatManager;
use Enhavo\Bundle\MediaBundle\Model\FilterSetting;

class MimetypeFilter extends AbstractFilter
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
        $mimeType = $file->getMimeType();
        $format = $setting->getSetting('format');
        $mimeTypes = $setting->getSetting('mimetypes', []);
        $settings = $this->formatManager->getFormatSettings($format);
        foreach ($settings as $setting) {
            if (in_array($mimeType, $mimeTypes)) {
                $format = $this->formatManager->getFilter($setting->getType());
                $format->apply($file, $setting);
            }
        }
    }

    public function getType()
    {
        return 'mimetype';
    }
}
