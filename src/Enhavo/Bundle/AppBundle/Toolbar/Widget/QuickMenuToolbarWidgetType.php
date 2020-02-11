<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-11
 * Time: 14:21
 */

namespace Enhavo\Bundle\AppBundle\Toolbar\Widget;

use Enhavo\Bundle\AppBundle\Toolbar\AbstractToolbarWidgetType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuickMenuToolbarWidgetType extends AbstractToolbarWidgetType
{
    public function createViewData(array $options)
    {
        $data = parent::createViewData($options);
        $data['menu'] = $options['menu'];
        $data['icon'] = $options['icon'];
        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
           'icon' => null,
            'component' => 'quick-menu-widget'
        ]);

        $resolver->setRequired([
            'menu'
        ]);
    }

    public function getType()
    {
        return 'quick_menu';
    }
}
