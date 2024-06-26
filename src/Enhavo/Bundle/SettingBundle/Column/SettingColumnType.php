<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 05/09/16
 * Time: 12:41
 */

namespace Enhavo\Bundle\SettingBundle\Column;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Enhavo\Bundle\SettingBundle\Entity\Setting;
use Enhavo\Bundle\SettingBundle\Setting\SettingManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class SettingColumnType extends AbstractColumnType
{
    public function __construct(
        private readonly SettingManager $settingManager
    )
    {
    }

    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void
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
            'sortingProperty' => null,
            'component' => 'column-text',
            'property' => 'value'
        ]);
        $resolver->setRequired(['property']);
    }

    public static function getName(): ?string
    {
        return 'setting';
    }
}
