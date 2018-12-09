<?php
/**
 * TableViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Controller\RequestConfigurationInterface;
use Enhavo\Bundle\AppBundle\Viewer\AbstractViewer;
use Enhavo\Bundle\AppBundle\Viewer\OptionAccessor;
use FOS\RestBundle\View\View;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableViewer extends AbstractViewer
{
    protected function getColumns()
    {
        $columns = $this->optionAccessor->get('columns');

        if(empty($columns)) {
            /** @var RequestConfiguration $configuration */
            $configuration = $this->configuration;

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
                $column['translationDomain'] = $this->optionAccessor->get('translationDomain');
            }
        }

        return $columns;
    }
    
    protected function getBatches()
    {
        $requestFactory = $this->container->get('sylius.resource_controller.request_configuration_factory');
        $router = $this->container->get('router');
        /** @var RequestConfigurationInterface $configuration */
        $configuration = $requestFactory->createFromRoute($this->getBatchRoute(), $this->metadata, $router);
        if($configuration === null) {
            return [];
        }
        $batches = $configuration->getBatches();
        return $batches;
    }

    protected function getDefaultMoveAfterRoute()
    {
        return sprintf('%s_%s_move_after', $this->metadata->getApplicationName(), $this->getUnderscoreName());
    }

    protected function getDefaultMoveToPageRoute()
    {
        return sprintf('%s_%s_move_to_page', $this->metadata->getApplicationName(), $this->getUnderscoreName());
    }

    protected function getBatchRoute()
    {
        return sprintf('%s_%s_batch', $this->metadata->getApplicationName(), $this->getUnderscoreName());
    }

    protected function getPermissionRole()
    {
        return strtoupper(sprintf('ROLE_%s_%s_DELETE', $this->metadata->getApplicationName(), $this->getUnderscoreName()));
    }

    public function getType()
    {
        return 'table';
    }

    public function createView($options = []): View
    {
        /** @var RequestConfiguration $configuration */
        $configuration = $this->configuration;

        $view = parent::createView($options);
        $view->setTemplate($this->configuration->getTemplate('EnhavoAppBundle:Viewer:table.html.twig'));
        $view->setTemplateData(array_merge($view->getTemplateData(), [
            'data' => $this->resource,
            'sortable' => $configuration->isSortable(),
            'columns' => $this->getColumns(),
            'batches' => $this->getBatches(),
            'batch_route' => $this->getBatchRoute(),
            'width' => $this->optionAccessor->get('width'),
            'move_after_route' => $this->optionAccessor->get('sorting.move_after_route'),
            'move_to_page_route' => $this->optionAccessor->get('sorting.move_to_page_route'),
        ]));
        return $view;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'width' => 12,
            'sorting' => [
                'move_after_route' => $this->getDefaultMoveAfterRoute(),
                'move_to_page_route' => $this->getDefaultMoveToPageRoute()
            ],
            'batch_route' => $this->getBatchRoute(),
            'columns' => []
        ]);
    }
}
