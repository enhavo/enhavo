<?php
/**
 * IndexViewer.php
 *
 * @since 26/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Batch\BatchManager;
use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Filter\FilterManager;
use Enhavo\Bundle\AppBundle\Column\ColumnManager;
use Enhavo\Bundle\AppBundle\Viewer\ViewerUtil;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndexViewer extends AppViewer
{
    /**
     * @var ActionManager
     */
    private $actionManager;

    /**
     * @var BatchManager
     */
    private $batchManager;

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * @var ColumnManager
     */
    private $columnManager;


    public function __construct(
        RequestConfigurationFactory $requestConfigurationFactory,
        ViewerUtil $util,
        ActionManager $actionManager,
        BatchManager $batchManager,
        FilterManager $filterManager,
        ColumnManager $columnManager
    ) {
        parent::__construct($requestConfigurationFactory, $util);
        $this->actionManager = $actionManager;
        $this->batchManager = $batchManager;
        $this->filterManager = $filterManager;
        $this->columnManager = $columnManager;
    }

    public function getType()
    {
        return 'index';
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        parent::buildTemplateParameters($parameters, $requestConfiguration, $options);

        $label = $this->mergeConfig([
            $options['label'],
            $this->getViewerOption('label', $requestConfiguration)
        ]);

        $actions = $this->mergeConfigArray([
            $this->createActions($options),
            $options['actions'],
            $this->getViewerOption('actions', $requestConfiguration)
        ]);

        $tableRoute = $this->mergeConfig([
            $this->getTableRoute($options),
            $options['table_route'],
            $this->getViewerOption('table_route', $requestConfiguration)
        ]);

        $tableRouteParameters = $this->mergeConfig([
            $options['table_route_parameters'],
            $this->getViewerOption('table_route_parameters', $requestConfiguration)
        ]);

        $tableConfiguration = $this->util->createConfigurationFromRoute($tableRoute);

        $filterData = $tableConfiguration ? $tableConfiguration->getFilters() : [];
        $columnData = $tableConfiguration ? $this->getViewerOption('columns', $tableConfiguration) : [];

        if($filterData) {
            $actions = $this->addFilterAction($actions);
        }

        $batchRoute = $this->mergeConfig([
            $this->getBatchRoute($options),
            $options['batch_route'],
            $this->getViewerOption('batch_route', $requestConfiguration)
        ]);

        $batchRouteParameters = $this->mergeConfig([
            $options['batch_route_parameters'],
            $this->getViewerOption('batch_route_parameters', $requestConfiguration)
        ]);

        $openRoute = $this->mergeConfig([
            $this->getOpenRoute($options),
            $options['open_route'],
            $this->getViewerOption('open_route', $requestConfiguration)
        ]);

        $openRouteParameters = $this->mergeConfig([
            $options['open_route_parameters'],
            $this->getViewerOption('open_route_parameters', $requestConfiguration)
        ]);

        $batchConfiguration = $this->util->createConfigurationFromRoute($batchRoute);
        $batchData = !empty($batchConfiguration) ? $batchConfiguration->getBatches() : [];

        /** @var Request $request */
        $request = $requestConfiguration->getRequest();

        $viewerOptions = $requestConfiguration->getViewerOptions();
        if(isset($viewerOptions['translation_domain'])) {
            $this->addTranslationDomain($columnData, $viewerOptions['translation_domain']);
        }

        $grid = [
            'tableRoute' => $tableRoute,
            'tableRouteParameters' => $tableRouteParameters,
            'batchRoute' => $batchRoute,
            'batchRouteParameters' => $batchRouteParameters,
            'openRoute' => $openRoute,
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
        ];

        $parameters->set('data', [
            'messages' => [],
            'grid' => $grid,
            'actions' => $this->actionManager->createActionsViewData($actions),
            'view' => [
                'id' => $this->getViewId(),
                'label' => $this->container->get('translator')->trans($label, [], $parameters->get('translation_domain'))
            ],
            'modals' => [],
        ]);

        return;
    }

    private function addTranslationDomain(&$configuration, $translationDomain)
    {
        foreach($configuration as &$config) {
            if(!isset($config['translation_domain']) && $translationDomain) {
                $config['translation_domain'] = $translationDomain;
            }
        }
    }

    private function getTableRoute($options)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];
        return sprintf('%s_%s_table', $metadata->getApplicationName(), $this->getUnderscoreName($metadata));
    }

    private function getBatchRoute($options)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];
        return sprintf('%s_%s_batch', $metadata->getApplicationName(), $this->getUnderscoreName($metadata));
    }

    private function getOpenRoute($options)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];
        return sprintf('%s_%s_update', $metadata->getApplicationName(), $this->getUnderscoreName($metadata));
    }

    private function createActions($options)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        $default = [
            'create' => [
                'type' => 'create',
                'route' => sprintf('%s_%s_create', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
                'permission' => $this->getRoleNameByResourceName($metadata->getApplicationName(), $this->getUnderscoreName($metadata), 'create')
            ]
        ];

        return $default;
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

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'javascripts' => [
                'enhavo/app/index'
            ],
            'stylesheets' => [
                'enhavo/app/index'
            ],
            'actions' => [],
            'table_route' => null,
            'table_route_parameters' => null,
            'batch_route' => null,
            'batch_route_parameters' => null,
            'open_route' => null,
            'open_route_parameters' => null,
            'label' => 'label.index',
            'translation_domain' => 'EnhavoAppBundle'
        ]);
    }
}
