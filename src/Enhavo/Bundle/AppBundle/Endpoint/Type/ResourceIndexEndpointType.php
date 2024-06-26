<?php
/**
 * IndexViewer.php
 *
 * @since 26/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Enhavo\Bundle\ResourceBundle\Column\ColumnManager;
use Enhavo\Bundle\ResourceBundle\Endpoint\Type\AppViewType;
use Enhavo\Bundle\ResourceBundle\Endpoint\Type\BatchManager;
use Enhavo\Bundle\ResourceBundle\Filter\FilterManager;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResourceIndexEndpointType extends AbstractEndpointType
{
    use ResourceMetadataHelperTrait;

    public function __construct(
        private ActionManager $actionManager,
        private BatchManager $batchManager,
        private FilterManager $filterManager,
        private ColumnManager $columnManager,
        private ViewUtil $util,
        private TranslatorInterface $translator
    ) {}

    public static function getName(): ?string
    {
        return 'index';
    }

    public static function getParentType(): ?string
    {
        return AppViewType::class;
    }

    public function createViewData($options, ViewData $data)
    {
        $requestConfiguration = $this->getRequestConfiguration($options);

        $this->util->isGrantedOr403($requestConfiguration, ResourceActions::INDEX);

        $label = $this->util->mergeConfig([
            $options['label'],
            $this->util->getViewerOption('label', $requestConfiguration)
        ]);

        $actions = $this->util->mergeConfigArray([
            $this->createActions($options),
            $options['actions'],
            $this->util->getViewerOption('actions', $requestConfiguration)
        ]);

        $tableRoute = $this->util->mergeConfig([
            $this->getTableRoute($options),
            $options['table_route'],
            $this->util->getViewerOption('table_route', $requestConfiguration)
        ]);

        $tableRouteParameters = $this->util->mergeConfig([
            $options['table_route_parameters'],
            $this->util->getViewerOption('table_route_parameters', $requestConfiguration)
        ]);

        $tableConfiguration = $this->util->createConfigurationFromRoute($tableRoute);

        $filterData = $tableConfiguration ? $tableConfiguration->getFilters() : [];
        $columnData = $tableConfiguration ? $this->util->getViewerOption('columns', $tableConfiguration) : [];

        if($filterData) {
            $actions = $this->addFilterAction($actions);
        }

        $batchRoute = $this->util->mergeConfig([
            $this->getBatchRoute($options),
            $options['batch_route'],
            $this->util->getViewerOption('batch_route', $requestConfiguration)
        ]);

        $batchRouteParameters = $this->util->mergeConfig([
            $options['batch_route_parameters'],
            $this->util->getViewerOption('batch_route_parameters', $requestConfiguration)
        ]);

        $openRoute = $this->util->mergeConfig([
            $this->getOpenRoute($options),
            $options['open_route'],
            $this->util->getViewerOption('open_route', $requestConfiguration)
        ]);

        $openRouteParameters = $this->util->mergeConfig([
            $options['open_route_parameters'],
            $this->util->getViewerOption('open_route_parameters', $requestConfiguration)
        ]);

        $batchConfiguration = $this->util->createConfigurationFromRoute($batchRoute);
        $batchData = !empty($batchConfiguration) ? $batchConfiguration->getBatches() : [];

        $request = $requestConfiguration->getRequest();

        $viewerOptions = $requestConfiguration->getViewerOptions();
        if(isset($viewerOptions['translation_domain'])) {
            $this->addTranslationDomain($columnData, $viewerOptions['translation_domain']);
        }

        $openClickable = $this->util->mergeConfig([
            $options['open_clickable'],
            $this->util->getViewerOption('open_clickable', $requestConfiguration)
        ]);

        $grid = [
            'tableRoute' => $tableRoute,
            'tableRouteParameters' => $tableRouteParameters,
            'batchRoute' => $batchRoute,
            'batchRouteParameters' => $batchRouteParameters,
            'openRoute' => $openRoute,
            'openClickable' => $openClickable,
            'openRouteParameters' => $openRouteParameters,
            'page' => $request->get('page', 1),
            'batches' => $this->batchManager->createBatchesViewData($batchData),
            'columns' => $this->columnManager->createColumnsViewData($columnData),
            'filters' => $this->filterManager->createFiltersViewData($filterData),
            'paginate' => $tableConfiguration->isPaginated(),
            'pagination' => 100,
            'paginationSteps' => [
                5, 10, 50, 100, 500
            ],
            'cssClass' => $this->util->getViewerOption('css_class', $requestConfiguration)
        ];

        $data['messages'] = [];
        $data['grid'] = $grid;
        $data['actions'] = $this->actionManager->createActionsViewData($actions);
        $data['label'] = $this->translator->trans($label, [], $options['translation_domain']);
        $data['modals'] = [];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'application' => '@enhavo/app/index/IndexApp',
            'component' => '@enhavo/app/components/index/IndexComponent.vue',
            'request_configuration' => null,
            'actions' => [],
            'table_route' => null,
            'table_route_parameters' => null,
            'batch_route' => null,
            'batch_route_parameters' => null,
            'open_route' => null,
            'open_clickable' => true,
            'open_route_parameters' => null,
            'label' => 'label.index',
            'translation_domain' => 'EnhavoAppBundle'
        ]);
    }

    private function addTranslationDomain(&$configuration, $translationDomain)
    {
        foreach($configuration as &$config) {
            if(!isset($config['translation_domain']) && $translationDomain) {
                $config['translation_domain'] = $translationDomain;
            }
        }
    }

    private function getTableRoute($options): string
    {
        $metadata = $this->getMetadata($options);
        return sprintf('%s_%s_table', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata));
    }

    private function getBatchRoute($options): string
    {
        $metadata = $this->getMetadata($options);
        return sprintf('%s_%s_batch', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata));
    }

    private function getOpenRoute($options): string
    {
        $metadata = $this->getMetadata($options);
        return sprintf('%s_%s_update', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata));
    }

    private function createActions($options): array
    {
        $metadata = $this->getMetadata($options);

        return [
            'create' => [
                'type' => 'create',
                'route' => sprintf('%s_%s_create', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata)),
                'permission' => $this->util->getRoleNameByResourceName($metadata->getApplicationName(), $this->util->getUnderscoreName($metadata), 'create')
            ]
        ];
    }

    private function addFilterAction($actions)
    {
        if(!isset($actions['filter'])) {
            $actions['filter'] = [
                'type' => 'filter'
            ];
        }
        return $actions;
    }


    ### List

    public function handleRequest($options, Request $request, ViewData $viewData, TemplateData $templateData)
    {
        $configuration = $this->getRequestConfiguration($options);
        $this->util->isGrantedOr403($configuration, ResourceActions::INDEX);
    }

    public function createViewData($options, ViewData $data)
    {
        $configuration = $this->getRequestConfiguration($options);

        $label = $this->util->mergeConfig([
            $options['label'],
            $this->util->getViewerOption('label', $configuration)
        ]);

        $actions = $this->util->mergeConfigArray([
            $this->createActions($options),
            $options['actions'],
            $this->util->getViewerOption('actions', $configuration)
        ]);

        $dataRoute = $this->util->mergeConfig([
            $this->getDataRoute($options),
            $options['data_route'],
            $this->util->getViewerOption('data_route', $configuration)
        ]);

        $dataRouteParameters = $this->util->mergeConfig([
            $options['data_route_parameters'],
            $this->util->getViewerOption('data_route_parameters', $configuration)
        ]);


        $dataConfiguration = $this->util->createConfigurationFromRoute($dataRoute);
        if($dataConfiguration == null) {
            throw new \Exception(sprintf('Data route "%s" for list viewer is not defined', $dataRoute));
        }
        $columnData = $this->util->getViewerOption('columns', $dataConfiguration);
        $positionProperty = $this->util->getViewerOption('position_property', $dataConfiguration);
        $parentProperty = $this->util->getViewerOption('parent_property', $dataConfiguration);
        $expanded = $this->util->getViewerOption('expanded', $dataConfiguration);

        $openRoute = $this->util->mergeConfig([
            $this->getopenRoute($options),
            $options['open_route'],
            $this->util->getViewerOption('open_route', $configuration)
        ]);

        $openRouteParameters = $this->util->mergeConfig([
            $options['open_route_parameters'],
            $this->util->getViewerOption('open_route_parameters', $configuration)
        ]);

        $viewerOptions = $configuration->getViewerOptions();
        if(isset($viewerOptions['translation_domain'])) {
            $this->addTranslationDomain($columnData, $viewerOptions['translation_domain']);
        }

        $data['list'] = [
            'dataRoute' => $dataRoute,
            'dataRouteParameters' => $dataRouteParameters,
            'openRoute' => $openRoute,
            'openRouteParameters' => $openRouteParameters,
            'columns' => $this->columnManager->createColumnsViewData($columnData),
            'items' => [],
            'positionProperty' => $positionProperty,
            'parentProperty' => $parentProperty,
            'expanded' => $expanded,
            'sortable' => $dataConfiguration->isSortable(),
            'cssClass' => $this->util->getViewerOption('css_class', $configuration)
        ];

        $data['messages'] = [];
        $data['actions'] = $this->actionManager->createActionsViewData($actions);
        $data['label'] = $this->translator->trans($label, [], $options['translation_domain']);
    }

    private function addTranslationDomain(&$configuration, $translationDomain)
    {
        foreach ($configuration as &$config) {
            if(!isset($config['translation_domain']) && $translationDomain) {
                $config['translation_domain'] = $translationDomain;
            }
        }
    }

    private function getDataRoute($options)
    {
        $metadata = $this->getMetadata($options);
        return sprintf('%s_%s_data', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata));
    }

    private function getopenRoute($options)
    {
        $metadata = $this->getMetadata($options);
        return sprintf('%s_%s_update', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata));
    }

    private function createActions($options)
    {
        $metadata = $this->getMetadata($options);

        return [
            'create' => [
                'type' => 'create',
                'route' => sprintf('%s_%s_create', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata)),
                'permission' => $this->util->getRoleNameByResourceName($metadata->getApplicationName(), $this->util->getUnderscoreName($metadata), 'create')
            ]
        ];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'application' => '@enhavo/app/list/ListApp',
            'component' => '@enhavo/app/components/list/ListApplicationComponent.vue',
            'actions' => [],
            'data_route' => null,
            'data_route_parameters' => null,
            'open_route' => null,
            'open_route_parameters' => null,
            'translation_domain' => 'EnhavoAppBundle',
            'label' => 'label.index',
        ]);

        $optionsResolver->setRequired('request_configuration');
    }
}
