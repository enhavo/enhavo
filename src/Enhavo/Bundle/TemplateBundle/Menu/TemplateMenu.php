<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 25.05.19
 * Time: 14:02
 */

namespace Enhavo\Bundle\TemplateBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'chrome_reader_mode',
            'label' => 'template.label.template',
            'translation_domain' => 'EnhavoTemplateBundle',
            'route' => 'enhavo_template_admin_template_index',
            'role' => 'ROLE_ENHAVO_TEMPLATE_TEMPLATE_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'template';
    }
}
