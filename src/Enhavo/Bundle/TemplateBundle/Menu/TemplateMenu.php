<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TemplateBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
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
            'permission' => 'ROLE_ENHAVO_TEMPLATE_TEMPLATE_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'template';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
