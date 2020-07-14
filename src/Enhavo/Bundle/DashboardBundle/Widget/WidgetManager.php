<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-21
 * Time: 16:55
 */

namespace Enhavo\Bundle\DashboardBundle\Widget;


use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class WidgetManager
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    /**
     * @var array
     */
    private $configuration;


    /**
     * WidgetManager constructor.
     * @param FactoryInterface $factory
     * @param AuthorizationCheckerInterface $checker
     * @param array $configuration
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $checker, array $configuration)
    {
        $this->factory = $factory;
        $this->checker = $checker;
        $this->configuration = $configuration;
    }

    public function createViewData()
    {
        $data = [];
        $dashboardWidgets = $this->getDashboardWidgets($this->configuration);
        foreach($dashboardWidgets as $dashboardWidget) {
            $data[] = $dashboardWidget->createViewData();
        }
        return $data;
    }

    /**
     * @param array $configuration
     * @return Widget[]
     */
    public function getDashboardWidgets(array $configuration)
    {
        $dashboardWidgets = [];
        foreach($configuration as $name => $options) {
            $dashBoardWidget = $this->createDashboardWidget($options);

            if($dashBoardWidget->isHidden()) {
                continue;
            }

            if($dashBoardWidget->getPermission() !== null && !$this->checker->isGranted($dashBoardWidget->getPermission())) {
                continue;
            }

            $dashboardWidgets[$name] = $dashBoardWidget;
        }

        return $dashboardWidgets;
    }

    /**
     * @param $options
     * @return Widget
     */
    private function createDashboardWidget($options)
    {
        return $this->factory->create($options);
    }
}
