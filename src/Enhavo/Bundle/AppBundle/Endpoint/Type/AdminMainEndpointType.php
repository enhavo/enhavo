<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Menu\MenuManager;
use Enhavo\Bundle\AppBundle\Toolbar\ToolbarManager;
use Enhavo\Bundle\AppBundle\Util\StateEncoder;
use Symfony\Component\HttpFoundation\Request;

class AdminMainEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly array $toolbarConfigurationPrimary,
        private readonly array $toolbarConfigurationSecondary,
        private readonly ToolbarManager $toolbarManager,
        private readonly array $brandingConfiguration,
        private readonly array $menuConfiguration,
        private readonly MenuManager $menuManager,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $data->set('toolbarWidgetsPrimary', $this->getToolbarWidgetViewData($this->toolbarConfigurationPrimary));
        $data->set('toolbarWidgetsSecondary', $this->getToolbarWidgetViewData($this->toolbarConfigurationSecondary));
        $data->set('branding', $this->getBrandingData());
        $data->set('menu', $this->getMenuData());
    }

    private function getToolbarWidgetViewData(array $configuration): array
    {
        $data = [];
        $widgets = $this->toolbarManager->getToolbarWidgets($configuration);
        foreach ($widgets as $widget) {
            $data[] = $widget->createViewData();
        }
        return $data;
    }

    private function getBrandingData(): array
    {
        return [
            'logo' => $this->brandingConfiguration['logo'],
            'enable' => $this->brandingConfiguration['enable'],
            'enableVersion' => $this->brandingConfiguration['enable_version'],
            'enableCreatedBy' => $this->brandingConfiguration['enable_created_by'],
            'text' => $this->brandingConfiguration['text'],
            'version' => $this->brandingConfiguration['version'],
            'backgroundImage' => $this->brandingConfiguration['background_image']
        ];
    }

    public function getMenuData(): array
    {
        $data = [];
        $items = $this->menuManager->getMenuItems($this->menuConfiguration);
        foreach ($items as $item) {
            $data[] = $item->createViewData();
        }
        return $data;
    }
}
