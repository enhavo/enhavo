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

class MimetypeFilter extends AbstractFilter
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

    /**
     * @inheritDoc
     */
    public function predictExtension(?string $originalExtension, FilterSetting $setting): ?string
    {
        return $originalExtension;
    }

    public function getType()
    {
        return 'mimetype';
    }
}
