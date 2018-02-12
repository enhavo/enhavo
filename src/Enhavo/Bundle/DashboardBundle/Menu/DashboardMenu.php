<?php
/**
 * DashboardMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\DashboardBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class DashboardMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setDefaultOption('icon', $options, 'stopwatch');
        $this->setDefaultOption('label', $options, 'dashboard.label.dashboard');
        $this->setDefaultOption('translationDomain', $options, 'EnhavoDashboardBundle');
        $this->setDefaultOption('route', $options, 'enhavo_dashboard_index');
        $this->setDefaultOption('role', $options, 'ROLE_ENHAVO_DASHBOARD_DASHBOARD_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'dashboard';
    }
}