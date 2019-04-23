<?php
/**
 * DashboardMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\DashboardBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DashboardMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'dashboard',
            'label' => 'dashboard.label.dashboard',
            'translation_domain' => 'EnhavoDashboardBundle',
            'route' => 'enhavo_dashboard_index',
            'role' => 'ROLE_ENHAVO_DASHBOARD_DASHBOARD_INDEX',
        ]);
    }

    public function getType()
    {
        return 'dashboard';
    }
}