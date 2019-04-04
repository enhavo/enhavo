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

        $tableConfiguration = $this->util->createConfigurationFromRoute($tableRoute);

        $filterData = $tableConfiguration->getFilters();
        $columnData = $this->getViewerOption('columns', $tableConfiguration);

        $batchRoute = $this->mergeConfig([
            $this->getBatchRoute($options),
            $options['batch_route'],
            $this->getViewerOption('batch_route', $requestConfiguration)
        ]);

        $batchConfiguration = $this->util->createConfigurationFromRoute($batchRoute);
        $batchData = $batchConfiguration->getBatches();

        /** @var Request $request */
        $request = $requestConfiguration->getRequest();

        $viewerOptions = $requestConfiguration->getViewerOptions();
        if(isset($viewerOptions['translationDomain'])) {
            $this->addTranslationDomain($columnData, $viewerOptions['translationDomain']);
        }

        $grid = [
            'tableRoute' => $tableRoute,
            'batchRoute' => $batchRoute,
            'page' => $request->get('page', 1),
            'batches' => $this->batchManager->createBatchesViewData($batchData),
            'columns' => $this->columnManager->createColumnsViewData($columnData),
            'filters' => $this->filterManager->createFiltersViewData($filterData),
            'pagination' => 100,
            'paginationSteps' => [
                5, 10, 50, 100, 500
            ],
        ];
        
        $parameters->set('data', [
            'grid' => $grid,
            'actions' => $this->actionManager->createActionsViewData($actions),
            'view_id' => $request->get('view_id', null),
        ]);

        return;
    }

    private function addTranslationDomain(&$configuration, $translationDomain)
    {
        foreach($configuration as &$config) {
            if(!isset($config['translation_domain'])) {
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

    private function createActions($options)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        $default = [
            'create' => [
                'type' => 'create',
                'route' => sprintf('%s_%s_create', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            ]
        ];

        return $default;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'javascripts' => [
                'enhavo/index'
            ],
            'stylesheets' => [
                'enhavo/index'
            ],
            'actions' => [],
            'table_route' => null,
            'batch_route' => null
        ]);
    }
}
