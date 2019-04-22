<?php
/**
 * ListDataViewer.php
 *
 * @since 22/04/19
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Column\ColumnManager;
use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Viewer\AbstractViewer;
use Enhavo\Bundle\AppBundle\Viewer\ViewerUtil;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListDataViewer extends AbstractViewer
{
    /**
     * @var ColumnManager
     */
    private $columnManager;

    public function __construct(RequestConfigurationFactory $requestConfigurationFactory, ViewerUtil $util, ColumnManager $columnManager)
    {
        parent::__construct($requestConfigurationFactory, $util);
        $this->columnManager = $columnManager;
    }

    public function getType()
    {
        return 'list_data';
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        parent::buildTemplateParameters($parameters, $requestConfiguration, $options);

        $columns = $this->mergeConfigArray([
            $options['columns'],
            $this->getViewerOption('columns', $requestConfiguration)
        ]);

        $childrenProperty = $this->mergeConfig([
            $options['children_property'],
            $this->getViewerOption('children_property', $requestConfiguration)
        ]);

        $positionProperty = $this->mergeConfig([
            $options['position_property'],
            $this->getViewerOption('position_property', $requestConfiguration)
        ]);

        $parameters->set('resources', $this->columnManager->createResourcesViewData($columns, $options['resources'], $childrenProperty, $positionProperty));
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'template' => 'EnhavoAppBundle:Viewer:data.html.twig',
            'columns' => [],
            'children_property' => null,
            'position_property' => null,
        ]);
    }
}
