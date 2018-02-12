<?php
/**
 * PageMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\PageBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class PageMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setDefaultOption('icon', $options, 'news');
        $this->setDefaultOption('label', $options, 'page.label.page');
        $this->setDefaultOption('translationDomain', $options, 'EnhavoPageBundle');
        $this->setDefaultOption('route', $options, 'enhavo_page_page_index');
        $this->setDefaultOption('role', $options, 'ROLE_ENHAVO_PAGE_PAGE_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'page';
    }
}