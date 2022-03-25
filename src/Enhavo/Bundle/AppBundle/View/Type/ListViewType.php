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
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Enhavo\Bundle\AppBundle\Viewer\ViewerUtil;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ListViewType extends AbstractViewType
{
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

    public function createViewData($options, ViewData $data)
    {
        $configuration = $this->util->getRequestConfiguration($options);

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
        $data['view'] = [
            'label' => $this->translator->trans($label, [], $options['translation_domain'])
        ];
    }

    private function addTranslationDomain(&$configuration, $translationDomain)
    {
        foreach($configuration as &$config) {
            if(!isset($config['translation_domain']) && $translationDomain) {
                $config['translation_domain'] = $translationDomain;
            }
        }
    }

    private function getDataRoute($options)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];
        return sprintf('%s_%s_data', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata));
    }

    private function getopenRoute($options)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];
        return sprintf('%s_%s_update', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata));
    }

    private function createActions($options)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        $default = [
            'create' => [
                'type' => 'create',
                'route' => sprintf('%s_%s_create', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata)),
                'permission' => $this->util->getRoleNameByResourceName($metadata->getApplicationName(), $this->util->getUnderscoreName($metadata), 'create')
            ]
        ];

        return $default;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'entrypoint' => 'enhavo/app/list',
            'actions' => [],
            'data_route' => null,
            'data_route_parameters' => null,
            'open_route' => null,
            'open_route_parameters' => null,
            'translation_domain' => 'EnhavoAppBundle',
            'label' => 'label.index',
            'metadata' => null,
            'request_configuration' => null,
        ]);
    }
}
