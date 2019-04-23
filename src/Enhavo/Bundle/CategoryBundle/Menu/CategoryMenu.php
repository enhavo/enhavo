<?php
/**
 * CategoryMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\CategoryBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'apps',
            'label' => 'category.label.category',
            'translation_domain' => 'EnhavoCategoryBundle',
            'route' => 'enhavo_category_category_index',
            'role' => 'ROLE_ENHAVO_CATEGORY_CATEGORY_INDEX',
        ]);
    }

    public function getType()
    {
        return 'category';
    }
}