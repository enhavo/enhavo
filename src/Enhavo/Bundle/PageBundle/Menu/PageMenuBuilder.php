<?php
/**
 * PageMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\PageBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class PageMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'news');
        $this->setOption('label', $options, 'page.label.page');
        $this->setOption('translationDomain', $options, 'EnhavoPageBundle');
        $this->setOption('route', $options, 'enhavo_page_page_index');
        $this->setOption('role', $options, 'ROLE_ADMIN_ENHAVO_PAGE_PAGE_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'page';
    }
}