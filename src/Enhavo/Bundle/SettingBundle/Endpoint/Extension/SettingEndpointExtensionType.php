<?php

namespace Enhavo\Bundle\SettingBundle\Endpoint\Extension;

use Enhavo\Bundle\ApiBundle\Data\Data;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointTypeExtension;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\ViewEndpointType;
use Enhavo\Bundle\SettingBundle\Setting\SettingManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SettingEndpointExtensionType extends AbstractEndpointTypeExtension
{
    public function __construct(
        private readonly SettingManager $settingManager,
    )
    {
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
        } else if (is_array($options['settings'])) {
            $groups = $options['settings'];
        } else if (is_bool($options['settings'])) {
            if ($options['settings']) {
                $keys = $this->settingManager->getKeys();
                foreach ($keys as $key) {
                    $value = $this->settingManager->getValue($key);
                    if (is_scalar($value) || $value == null) {
                        $settings[$key] = $value;
                    } else {
                        $settings[$key] = $this->normalize($value, null, ['groups' => 'endpoint']);
                    }
                }
            }
        }

        foreach ($groups as $group) {
            $keys = $this->settingManager->getKeys();
            foreach ($keys as $key) {
                $setting = $this->settingManager->getSetting($key);
                if ($setting->getGroup() == $group) {
                    $value = $this->settingManager->getValue($key);
                    if (is_scalar($value) || $value == null) {
                        $settings[$key] = $setting->getValue();
                    } else {
                        $settings[$key] = $this->normalize($value, null, ['groups' => 'endpoint']);
                    }
                }
            }
        }
        $data->set('settings', $settings);
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
