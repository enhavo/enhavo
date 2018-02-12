<?php
/**
 * CategoryMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\CategoryBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class CategoryMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setDefaultOption('icon', $options, 'th');
        $this->setDefaultOption('label', $options, 'category.label.category');
        $this->setDefaultOption('translationDomain', $options, 'EnhavoCategoryBundle');
        $this->setDefaultOption('route', $options, 'enhavo_category_category_index');
        $this->setDefaultOption('role', $options, 'ROLE_ENHAVO_CATEGORY_CATEGORY_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'category';
    }
}