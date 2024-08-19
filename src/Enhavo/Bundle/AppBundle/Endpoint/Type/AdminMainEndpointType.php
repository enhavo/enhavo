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
        $data->set('viewStack', $this->getViewStackData($request));
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

    private function getViewStackData(Request $request): array
    {
        $state = $this->getState($request);
        return [
            'width' => 0,
            'views' => $state['views'],
            'storage' => $state['storage'],
        ];
    }

    private function getState(Request $request): array
    {
        $default = [
            'views' => [],
            'storage' => [],
        ];

        if(!$request->query->has('state')) {
            return $default;
        }
        $state = $request->query->get('state');
        $state = StateEncoder::decode($state);
        if($state === null) {
            return $default;
        }

        $views = [];
        if(isset($state['views'])) {
            $views = $state['views'];
            $id = 1;
            foreach($views as &$view) {
                if(!isset($view['component'])) {
                    $view['component'] = 'iframe-view';
                }
                if(!isset($view['id'])) {
                    $view['id'] = $id++;
                }
                $view['loaded'] = false;
            }
        }

        $storage = [];
        if(isset($state['storage'])) {
            $storage = $state['storage'];
        }

        return [
            'views' => $views,
            'storage' => $storage
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
