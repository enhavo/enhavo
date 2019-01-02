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

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        parent::buildTemplateParameters($parameters, $requestConfiguration, $options);

        $parameters->set('data',  $options['resources']);

        $parameters->set('sortable', $this->mergeConfig([
            $options['sortable'],
            $requestConfiguration->isSortable(),
        ]));

        $parameters->set('columns', $this->mergeConfigArray([
            $options['columns'],
            $this->getColumns($requestConfiguration, $parameters->get('translationDomain'))
        ]));

        $parameters->set('width', $this->mergeConfig([
            $options['width']
        ]));

        $parameters->set('expand', $options['expand']);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'width' => 12,
            'columns' => [],
            'expand' => true,
            'sortable' => false,
            'template' => 'EnhavoAppBundle:Viewer:list.html.twig'
        ]);
    }
}
