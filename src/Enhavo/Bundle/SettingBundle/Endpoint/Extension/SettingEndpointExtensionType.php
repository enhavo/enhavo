<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SettingBundle\Endpoint\Extension;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointTypeExtension;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\ViewEndpointType;
use Enhavo\Bundle\SettingBundle\Setting\SettingManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingEndpointExtensionType extends AbstractEndpointTypeExtension
{
    public function __construct(
        private readonly SettingManager $settingManager,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $settings = [];

        if (empty($options['settings'])) {
            return;
        }

        $groups = [];
        if (is_string($options['settings'])) {
            $groups[] = $options['settings'];
        } elseif (is_array($options['settings'])) {
            $groups = $options['settings'];
        } elseif (is_bool($options['settings'])) {
            $keys = $this->settingManager->getKeys();
            foreach ($keys as $key) {
                $settings[$key] = $this->normalizeSetting($key);
            }
        }

        foreach ($groups as $group) {
            $keys = $this->settingManager->getKeys();
            foreach ($keys as $key) {
                $setting = $this->settingManager->getSetting($key);
                if ($setting->getGroup() == $group) {
                    $settings[$key] = $this->normalizeSetting($key);
                }
            }
        }
        $data->set('settings', $settings);
    }

    private function normalizeSetting($key)
    {
        $value = $this->settingManager->getValue($key);
        if (is_scalar($value) || null == $value) {
            return $value;
        }

        return $this->normalize($value, null, ['groups' => 'endpoint']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'settings' => null,
        ]);
    }

    public static function getExtendedTypes(): array
    {
        return [ViewEndpointType::class];
    }
}
