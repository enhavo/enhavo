<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DashboardBundle\Dashboard;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DashboardManager
{
    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly AuthorizationCheckerInterface $checker,
        private readonly array $configuration,
    ) {
    }

    public function createViewData(): array
    {
        $data = [];
        $dashboardWidgets = $this->getDashboardWidgets($this->configuration);
        foreach ($dashboardWidgets as $dashboardWidget) {
            $data[] = $dashboardWidget->createViewData();
        }

        return $data;
    }

    private function getDashboardWidgets(array $configuration): array
    {
        $dashboardWidgets = [];
        foreach ($configuration as $key => $options) {
            $dashboardWidget = $this->factory->create($options, $key);

            if (!$dashboardWidget->isEnabled()) {
                continue;
            }

            if (null !== $dashboardWidget->getPermission() && !$this->checker->isGranted($dashboardWidget->getPermission())) {
                continue;
            }

            $dashboardWidgets[$key] = $dashboardWidget;
        }

        return $dashboardWidgets;
    }
}
