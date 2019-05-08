<?php
/**
 * ListViewer.php
 *
 * @since 22/05/19
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Column\ColumnManager;
use Enhavo\Bundle\AppBundle\Viewer\ViewerUtil;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListViewer extends AppViewer
{
    /**
     * @var ActionManager
     */
    private $actionManager;

    /**
     * @var ColumnManager
     */
    private $columnManager;

    public function __construct(
        RequestConfigurationFactory $requestConfigurationFactory,
        ViewerUtil $util,
        ActionManager $actionManager,
        ColumnManager $columnManager
    ) {
        parent::__construct($requestConfigurationFactory, $util);
        $this->actionManager = $actionManager;
        $this->columnManager = $columnManager;
    }

    public function getType()
    {
        return 'list';
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        parent::buildTemplateParameters($parameters, $requestConfiguration, $options);

        $actions = $this->mergeConfigArray([
            $this->createActions($options),
            $options['actions'],
            $this->getViewerOption('actions', $requestConfiguration)
        ]);

        $dataRoute = $this->mergeConfig([
            $this->getDataRoute($options),
            $options['data_route'],
            $this->getViewerOption('data_route', $requestConfiguration)
        ]);


        $dataConfiguration = $this->util->createConfigurationFromRoute($dataRoute);
        if($dataConfiguration == null) {
            throw new \Exception(sprintf('Data route "%s" for list viewer is not defined', $dataRoute));
        }
        $columnData = $this->getViewerOption('columns', $dataConfiguration);
        $positionProperty = $this->getViewerOption('position_property', $dataConfiguration);
        $parentProperty = $this->getViewerOption('parent_property', $dataConfiguration);

        $updateRoute = $this->mergeConfig([
            $this->geUpdateRoute($options),
            $options['update_route'],
            $this->getViewerOption('update_route', $requestConfiguration)
        ]);
        
        $viewerOptions = $requestConfiguration->getViewerOptions();
        if(isset($viewerOptions['translationDomain'])) {
            $this->addTranslationDomain($columnData, $viewerOptions['translationDomain']);
        }

        $list = [
            'dataRoute' => $dataRoute,
            'updateRoute' => $updateRoute,
            'columns' => $this->columnManager->createColumnsViewData($columnData),
            'items' => [],
            'positionProperty' => $positionProperty,
            'parentProperty' => $parentProperty,
        ];

        $parameters->set('data', [
            'messages' => [],
            'list' => $list,
            'actions' => $this->actionManager->createActionsViewData($actions),
            'view' => [
                'id' => null,
            ]
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

    private function getDataRoute($options)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];
        return sprintf('%s_%s_data', $metadata->getApplicationName(), $this->getUnderscoreName($metadata));
    }

    private function geUpdateRoute($options)
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
            ]
        ];

        return $default;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'javascripts' => [
                'enhavo/list'
            ],
            'stylesheets' => [
                'enhavo/list'
            ],
            'actions' => [],
            'data_route' => null,
            'update_route' => null
        ]);
    }
}
