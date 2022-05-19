<?php
/**
 * ListViewer.php
 *
 * @since 22/05/19
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Column\ColumnManager;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Enhavo\Bundle\AppBundle\Viewer\ViewerUtil;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ListViewType extends AbstractViewType
{
    use ResourceMetadataHelperTrait;

    public function __construct(
        private ViewUtil $util,
        private ActionManager $actionManager,
        private ColumnManager $columnManager,
        private TranslatorInterface $translator,
    ) {}

    public static function getName(): ?string
    {
        return 'list';
    }

    public static function getParentType(): ?string
    {
        return AppViewType::class;
    }

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
            'component' => '@enhavo/app/list/components/ListApplicationComponent.vue',
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
