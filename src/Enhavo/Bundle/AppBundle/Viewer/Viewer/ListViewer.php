<?php
/**
 * TableViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Viewer\AbstractViewer;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListViewer extends AbstractViewer
{
    public function getType()
    {
        return 'list';
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        $parameters->set('data', $options['resources']);

        $parameters->set('sortable', $this->mergeConfig([
            $requestConfiguration->isSortable(),
            $options['sortable']
        ]));

        $parameters->set('columns', $this->getColumns($requestConfiguration));
        $parameters->set('batches', $this->getBatches($requestConfiguration));
        $parameters->set('batch_route', $this->getBatches($requestConfiguration));
        $parameters->set('width', $this->getViewerOption('width', $requestConfiguration));
        $parameters->set('expand', $this->getExpand());
    }

    protected function getColumns(RequestConfiguration $requestConfiguration)
    {
        $columns = $this->getViewerOption('columns', $requestConfiguration);

        if(empty($columns)) {
            if ($requestConfiguration->isSortable()) {
                $columns = array(
                    'id' => array(
                        'label' => 'id',
                        'property' => 'id',
                    ),
                    'position' => array(
                        'type' => 'position'
                    )
                );
            } else {
                $columns = array(
                    'id' => array(
                        'label' => 'id',
                        'property' => 'id',
                    )
                );
            }
        }

        foreach($columns as $key => &$column) {
            if(!array_key_exists('type', $column)) {
                $column['type'] = 'property';
            }
        }

        foreach($columns as $key => &$column) {
            if(!array_key_exists('translationDomain', $column)) {
                $column['translationDomain'] = $this->getViewerOption('translationDomain', $requestConfiguration);
            }
        }

        return $columns;
    }
    
    protected function getBatches(RequestConfiguration $requestConfiguration, $options)
    {
        $requestFactory = $this->container->get('sylius.resource_controller.request_configuration_factory');
        $router = $this->container->get('router');
        $configuration = $requestFactory->createFromRoute($this->getBatchRoute(), $options['metadata'], $router);
        if($configuration === null) {
            return [];
        }
        $batches = $configuration->getBatches();
        return $batches;
    }


    protected function getBatchRoute()
    {
        return sprintf('%s_%s_batch', $this->metadata->getApplicationName(), $this->getUnderscoreName());
    }

    protected function getPermissionRole()
    {
        return strtoupper(sprintf('ROLE_%s_%s_DELETE', $this->metadata->getApplicationName(), $this->getUnderscoreName()));
    }

    protected function getExpand()
    {
        return $this->optionAccessor->get('expand');
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'width' => 12,
            'batch_route' => $this->getBatchRoute(),
            'columns' => [],
            'expand' => true
        ]);
    }
}
