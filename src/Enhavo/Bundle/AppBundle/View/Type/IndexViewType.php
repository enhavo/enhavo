<?php
/**
 * IndexViewer.php
 *
 * @since 26/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Batch\BatchManager;
use Enhavo\Bundle\AppBundle\Filter\FilterManager;
use Enhavo\Bundle\AppBundle\Column\ColumnManager;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class IndexViewType extends AbstractViewType
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
}
