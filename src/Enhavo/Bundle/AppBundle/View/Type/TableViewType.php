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
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableViewType extends AbstractViewType
{
    public function __construct(
        private ViewUtil $util,
        private ColumnManager $columnManager
    ) {}

    private function getBatches($batchRoute)
    {
        $configuration = $this->util->createConfigurationFromRoute($batchRoute);
        if ($configuration) {
            $batches = $configuration->getBatches();
            return $batches;
        }
        return [];
    }

    public function createViewData($options, ViewData $data)
    {
        /** @var RequestConfiguration $requestConfiguration */
        $requestConfiguration = $options['request_configuration'];

        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        $data->set('data', $options['resources']);

        $data->set('sortable', $this->util->mergeConfig([
            $options['sortable'],
            $requestConfiguration->isSortable(),
        ]));

        $columns = $this->util->getViewerOption('columns', $requestConfiguration);

        $data->set('batch_route', $this->util->mergeConfig([
            sprintf('%s_%s_batch', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata)),
            $options['batch_route'],
        ]));

        $data->set('batches', $this->getBatches($data->get('batch_route')));

        $data->set('width', $this->util->mergeConfig([
            $options['width']
        ]));

        $resources = $options['resources'];
        if ($resources instanceof Pagerfanta) {
            $data->set('pages', [
                'count' => $resources->count(),
                'page' => $resources->getCurrentPage()
            ]);

            if (!$requestConfiguration->isPaginated()) {
                $resources->setMaxPerPage($resources->count());
            }
        }

        if ($requestConfiguration->getHydrate() === FilterQuery::HYDRATE_ID) {
            $data->set('resources', $options['resources']);
        } else {
            $data->set('resources', $this->columnManager->createResourcesViewData($columns, $options['resources']));
        }
    }

    public function getResponse($options, Request $request, ViewData $viewData, TemplateData $templateData): Response
    {
        return new JsonResponse($viewData->normalize());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'metadata' => null,
            'request_configuration' => null,
            'resources' => null,
            'width' => 12,
            'move_after_route' => null,
            'move_to_page_route' => null,
            'batch_route' => null,
            'columns' => [],
            'sortable' => false,
            'template' => 'admin/view/table.html.twig'
        ]);
    }

    public static function getName(): ?string
    {
        return 'table';
    }
}
