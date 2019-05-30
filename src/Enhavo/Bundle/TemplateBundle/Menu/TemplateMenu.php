<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 25.05.19
 * Time: 14:02
 */

namespace Enhavo\Bundle\TemplateBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'chrome_reader_mode',
            'label' => 'template.label.template',
            'translation_domain' => 'EnhavoTemplateBundle',
            'route' => 'enhavo_template_template_index',
            'role' => 'ROLE_ENHAVO_TEMPLATE_TEMPLATE_INDEX',
        ]);
    }

    public function getType()
    {
        return 'template';
    }
}