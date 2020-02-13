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

class NewWindowToolbarWidgetType extends AbstractToolbarWidgetType
{
    public function createViewData($name, array $options)
    {
        $data = parent::createViewData($name, $options);
        $data['label'] = $this->getLabel($options);
        $data['icon'] = $options['icon'];
        return $data;
    }

    private function getLabel($config)
    {
        return $this->translator->trans($config['label'], [], $config['translation_domain'] ? $config['translation_domain'] : null);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'icon' => 'open_in_new',
            'component' => 'new-window-widget',
            'translation_domain' => null,
            'label' => null
        ]);
    }

    public function getType()
    {
        return 'new_window';
    }
}
