<?php
/**
 * SettingManager.php
 *
 * @since 06/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\SettingBundle\Setting;

use Enhavo\Bundle\SettingBundle\Exception\SettingNotExists;
use Enhavo\Component\Type\FactoryInterface;

class SettingManager
{
    /** @var Setting[] */
    private $settings = [];

    /** @var array */
    private $settingConfig;

    /** @var FactoryInterface */
    private $factory;

    public function __construct(FactoryInterface $factory, array $settingConfig)
    {
        $this->settingConfig = $settingConfig;
        $this->factory = $factory;
    }

    public function getSetting($key)
    {
        return $this->getOrCreateSetting($key);
    }

    public function getValue($key)
    {
        return $this->getOrCreateSetting($key)->getValue();
    }

    public function getKeys()
    {
        return array_keys($this->settingConfig);
    }

    private function getOrCreateSetting($key)
    {
        if (array_key_exists($key, $this->settings)) {
            return $this->settings[$key];
        }

        if (!array_key_exists($key, $this->settingConfig)) {
            throw SettingNotExists::keyNotFound($key);
        }

        $setting = $this->factory->create($this->settingConfig[$key], $key);
        $this->settings[$key] = $setting;
        return $setting;
    }

    public function getFormType($key)
    {
        return $this->getOrCreateSetting($key)->getFormType();
    }

    public function getFormTypeOptions($key)
    {
        return $this->getOrCreateSetting($key)->getFormTypeOptions();
    }

    public function getViewValue($key, $value)
    {
        return $this->getOrCreateSetting($key)->getViewValue($value);
    }

    public function getSettingsByGroup($group) {
        $settings = [];
        foreach (array_keys($this->settingConfig) as $key) {
            $setting = $this->settingRepository->findBy(['group' => $group, 'key' => $key]);
            if ($setting) {
                $settings[] = $setting;
            }
        }
        return $settings;
    }
}
