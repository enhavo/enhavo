<?php
/**
 * TableViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Column\ColumnManager;
use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\Viewer\AbstractResourceViewer;
use Enhavo\Bundle\AppBundle\Viewer\ViewerUtil;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableViewType extends AbstractViewType
{
    /**
     * @var ColumnManager
     */
    private $columnManager;

    /**
     * TableViewer constructor.
     * @param RequestConfigurationFactory $requestConfigurationFactory
     * @param ViewerUtil $util
     * @param ColumnManager $columnManager
     */
    public function __construct(
        RequestConfigurationFactory $requestConfigurationFactory,
        ViewerUtil $util,
        ColumnManager $columnManager
        )
    {
        parent::__construct($requestConfigurationFactory, $util);
        $this->columnManager = $columnManager;
    }

    public function getType()
    {
        return 'table';
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

        $columns = $this->getViewerOption('columns', $requestConfiguration);

        $parameters->set('batch_route', $this->mergeConfig([
            sprintf('%s_%s_batch', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            $options['batch_route'],
        ]));

        $parameters->set('batches', $this->getBatches($parameters->get('batch_route')));

        $parameters->set('width', $this->mergeConfig([
            $options['width']
        ]));

        $resources = $options['resources'];
        if($resources instanceof Pagerfanta) {
            $parameters->set('pages', [
                'count' => $resources->count(),
                'page' => $resources->getCurrentPage()
            ]);

            if (!$requestConfiguration->isPaginated()) {
                $resources->setMaxPerPage($resources->count());
            }
        }

        if ($requestConfiguration->getHydrate() === FilterQuery::HYDRATE_ID) {
            $parameters->set('resources', $options['resources']);
        } else {
            $parameters->set('resources', $this->columnManager->createResourcesViewData($columns, $options['resources']));
        }

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
            'template' => 'admin/view/table.html.twig'
        ]);
    }
}
