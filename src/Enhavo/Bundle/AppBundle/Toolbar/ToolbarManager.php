<?php

namespace Enhavo\Bundle\AppBundle\Toolbar;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ToolbarManager
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $checker,
        private readonly FactoryInterface $toolbarWidgetFactory,
    )
    {
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

            if ($widget->getPermission() !== null && !$this->checker->isGranted($widget->getPermission())) {
                continue;
            }

            $widgets[$key] = $widget;
        }

        return $widgets;
    }
}
