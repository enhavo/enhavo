<?php

namespace Enhavo\Bundle\SettingBundle\Twig;

use Enhavo\Bundle\SettingBundle\Setting\SettingManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    /** @var SettingManager */
    private $settingManager;

    /**
     * LocaleExtension constructor.
     * @param SettingManager $settingManager
     */
    public function __construct(SettingManager $settingManager)
    {
        $this->settingManager = $settingManager;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('setting', array($this, 'getSetting')),
        );
    }

    public function getSetting($key)
    {
        return $this->settingManager->getValue($key);
    }
}
