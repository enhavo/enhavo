<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 05/09/16
 * Time: 12:41
 */

namespace Enhavo\Bundle\SettingBundle\Column;

use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Enhavo\Bundle\SettingBundle\Entity\Setting;
use Enhavo\Bundle\SettingBundle\Setting\SettingManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingColumnType extends AbstractColumnType
{
    /** @var SettingManager */
    private $settingManager;

    /**
     * SettingColumnType constructor.
     * @param SettingManager $settingManager
     */
    public function __construct(SettingManager $settingManager)
    {
        $this->settingManager = $settingManager;
    }

    public function createResourceViewData(array $options, $resource)
    {
        /** @var $resource Setting */
        $value = $this->getProperty($resource, $options['property']);
        return $this->settingManager->getViewValue($resource->getKey(), $value);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'sortingProperty' => null,
            'component' => 'column-text',
            'property' => 'value'
        ]);
        $resolver->setRequired(['property']);
    }

    public function getType()
    {
        return 'setting';
    }
}
