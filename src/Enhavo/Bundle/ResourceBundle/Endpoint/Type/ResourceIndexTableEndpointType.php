<?php
/**
 * TableViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;


use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Enhavo\Bundle\ResourceBundle\Column\ColumnManager;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceIndexTableEndpointType extends AbstractEndpointType
{
    use ResourceMetadataHelperTrait;

    public function __construct(
        private ViewUtil $util,
        private ColumnManager $columnManager,
        private ResourceManager $resourceManager,
        private ResourcesCollectionProviderInterface $resourcesCollectionProvider,
    ) {}

    private function getBatches($batchRoute)
    {
        $configuration = $this->util->createConfigurationFromRoute($batchRoute);
        if ($configuration) {
            return $configuration->getBatches();
        }
        return [];
    }

    public function createViewData($options, ViewData $data)
    {
        $configuration = $this->getRequestConfiguration($options);
        $metadata = $this->getMetadata($options);

        $this->util->isGrantedOr403($configuration, ResourceActions::INDEX);
        $repository = $this->resourceManager->getRepository($metadata->getApplicationName(), $metadata->getName());
        $resources = $this->resourcesCollectionProvider->get($configuration, $repository);

        $data->set('data', $resources);

        $data->set('sortable', $this->util->mergeConfig([
            $options['sortable'],
            $configuration->isSortable(),
        ]));

        $columns = $this->util->getViewerOption('columns', $configuration);

        $data->set('batch_route', $this->util->mergeConfig([
            sprintf('%s_%s_batch', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata)),
            $options['batch_route'],
        ]));

        $data->set('batches', $this->getBatches($data->get('batch_route')));

        $data->set('width', $this->util->mergeConfig([
            $options['width']
        ]));

        if ($resources instanceof Pagerfanta) {
            $data->set('pages', [
                'count' => $resources->count(),
                'page' => $resources->getCurrentPage()
            ]);

            if (!$configuration->isPaginated()) {
                $resources->setMaxPerPage($resources->count());
            }
        }

        if ($configuration->getHydrate() === FilterQuery::HYDRATE_ID) {
            $data->set('resources', $resources);
        } else {
            $data->set('resources', $this->columnManager->createResourcesViewData($columns, $resources));
        }
    }

    public function getResponse($options, Request $request, ViewData $viewData, TemplateData $templateData): Response
    {
        return new JsonResponse($viewData->normalize());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'width' => 12,
            'move_after_route' => null,
            'move_to_page_route' => null,
            'batch_route' => null,
            'columns' => [],
            'sortable' => false,
            'template' => 'admin/view/table.html.twig'
        ]);

        $resolver->setRequired('request_configuration');
    }

    public static function getName(): ?string
    {
        return 'table';
    }
}
