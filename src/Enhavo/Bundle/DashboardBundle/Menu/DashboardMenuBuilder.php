<?php
/**
 * DashboardMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\DashboardBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class DashboardMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'stopwatch');
        $this->setOption('label', $options, 'dashboard.label.dashboard');
        $this->setOption('translationDomain', $options, 'EnhavoDashboardBundle');
        $this->setOption('route', $options, 'enhavo_dashboard_index');
        $this->setOption('role', $options, 'ROLE_ENHAVO_DASHBOARD_DASHBOARD_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'dashboard';
    }
}