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
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableViewer extends AbstractViewer
{
    public function getType()
    {
        return 'table';
    }

    private function getColumns(RequestConfiguration $configuration, $defaultTranslationDomain = null)
    {
        $columns = $this->getViewerOption('columns', $configuration);

        if(empty($columns)) {
            if ($configuration->isSortable()) {
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
                $column['translationDomain'] = $defaultTranslationDomain;
            }
        }

        return $columns;
    }
    
    private function getBatches($batchRoute)
    {
        $configuration = $this->util->createConfigurationFromRoute($batchRoute);
        if($configuration) {
            $batches = $configuration->getBatches();
            return $batches;
        }
        return [];
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        parent::buildTemplateParameters($parameters, $requestConfiguration, $options);

        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        $parameters->set('data',  $options['resources']);

        $parameters->set('sortable', $this->mergeConfig([
            $options['sortable'],
            $requestConfiguration->isSortable(),
        ]));

        $parameters->set('columns', $this->mergeConfigArray([
            $options['columns'],
            $this->getColumns($requestConfiguration, $parameters->get('translationDomain'))
        ]));

        $parameters->set('batch_route', $this->mergeConfig([
            sprintf('%s_%s_batch', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            $options['batch_route'],
        ]));

        $parameters->set('batches', $this->getBatches($parameters->get('batch_route')));

        $parameters->set('width', $this->mergeConfig([
            $options['width']
        ]));

        $parameters->set('move_after_route', $this->mergeConfig([
            sprintf('%s_%s_move_after', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            $options['move_after_route']
        ]));

        $parameters->set('move_to_page_route', $this->mergeConfig([
            sprintf('%s_%s_move_to_page', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            $options['move_to_page_route']
        ]));
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'width' => 12,
            'move_after_route' => null,
            'move_to_page_route' => null,
            'batch_route' => null,
            'columns' => [],
            'sortable' => false,
            'template' => 'EnhavoAppBundle:Viewer:table.html.twig'
        ]);
    }
}
