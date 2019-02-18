<?php
/**
 * PageMenu.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\PageBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'pages',
            'label' => 'page.label.page',
            'translation_domain' => 'EnhavoPageBundle',
            'route' => 'enhavo_page_page_index',
            'role' => 'ROLE_ENHAVO_PAGE_PAGE_INDEX',
        ]);
    }

    public function getType()
    {
        return 'page';
    }
}