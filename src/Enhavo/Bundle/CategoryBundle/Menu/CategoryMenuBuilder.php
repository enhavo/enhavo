<?php
/**
 * CategoryMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\CategoryBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class CategoryMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'th');
        $this->setOption('label', $options, 'category.label.category');
        $this->setOption('translationDomain', $options, 'EnhavoCategoryBundle');
        $this->setOption('route', $options, 'enhavo_category_category_index');
        $this->setOption('role', $options, 'ROLE_ADMIN_ENHAVO_CATEGORY_CATEGORY_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'category';
    }
}