<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Toolbar;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ToolbarManager
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $checker,
        private readonly FactoryInterface $toolbarWidgetFactory,
    ) {
    }

    /**
     * @return ToolbarWidget[]
     */
    public function getToolbarWidgets(array $configuration): array
    {
        $widgets = [];
        foreach ($configuration as $key => $options) {
            /** @var ToolbarWidget $widget */
            $widget = $this->toolbarWidgetFactory->create($options, $key);

            if (!$widget->isEnabled()) {
                continue;
            }

            if (null !== $widget->getPermission() && !$this->checker->isGranted($widget->getPermission())) {
                continue;
            }

            $widgets[$key] = $widget;
        }

        return $widgets;
    }
}
