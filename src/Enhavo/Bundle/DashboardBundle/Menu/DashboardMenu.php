<?php
/**
 * DashboardMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\DashboardBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DashboardMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'icon' => 'dashboard',
            'label' => 'dashboard.label.dashboard',
            'translation_domain' => 'EnhavoDashboardBundle',
            'route' => 'enhavo_dashboard_admin_index',
            'role' => 'ROLE_ENHAVO_DASHBOARD_DASHBOARD_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'dashboard';
    }
}
