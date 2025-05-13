<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SettingBundle\Column;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\SettingBundle\Entity\Setting;
use Enhavo\Bundle\SettingBundle\Setting\SettingManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class SettingColumnType extends AbstractColumnType
{
    public function __construct(
        private readonly SettingManager $settingManager,
    ) {
    }

    public function createResourceViewData(array $options, object $resource, Data $data): void
    {
        if ($resource instanceof Setting) {
            $propertyAccessor = new PropertyAccessor();
            $value = $propertyAccessor->getValue($resource, $options['property']);
            $data->set('value', $this->settingManager->getViewValue($resource->getKey(), $value));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-text',
            'property' => 'value',
            'model' => 'TextColumn',
        ]);
        $resolver->setRequired(['property']);
    }

    public static function getName(): ?string
    {
        return 'setting';
    }
}
