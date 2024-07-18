<?php

namespace Enhavo\Bundle\ResourceBundle\Collection;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Controller\SortingManager;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Enhavo\Bundle\ResourceBundle\Column\ColumnManager;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Filter\FilterManager;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class ListCollection extends AbstractCollectionResolver
{
    public function __construct(
        private readonly FilterManager $filterManager,
        private readonly RequestStack $requestStack,
        private readonly ResourceExpressionLanguage $expressionLanguage,
    )
    {}

    protected function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'children_property' => null,
            'parent_property' => true,
            'repository_method' => null,
            'repository_arguments' => null,
            'columns' => [],
            'component' => null,
        ]);
    }

    public function getResources(EntityRepository $repository, array $options = []): array|Pagerfanta|Collection
    {
        $options = $this->getOptions($options);

        return [];
    }
}



//private array $resources;
//
//public function __construct(
//    private ViewUtil $util,
//    private ColumnManager $columnManager,
//    private SortingManager $sortingManager,
//    private CsrfTokenManager $tokenManager,
//    private ResourcesCollectionProviderInterface $resourcesCollectionProvider,
//    private ResourceManager $resourceManager,
//) {}
//
//public static function getName(): ?string
//{
//    return 'list_data';
//}
//
//public function init($options)
//{
//    $configuration = $this->getRequestConfiguration($options);
//    $metadata = $this->getMetadata($options);
//
//    $repository = $this->resourceManager->getRepository($metadata->getApplicationName(), $metadata->getName());
//
//    $configuration->getParameters()->set('paginate', false);
//    $this->util->isGrantedOr403($configuration, ResourceActions::INDEX);
//    $this->resources = $this->resourcesCollectionProvider->get($configuration, $repository);
//}
//
//public function handleRequest($options, Request $request, ViewData $viewData, TemplateData $templateData)
//{
//    $configuration = $this->getRequestConfiguration($options);
//    $metadata = $this->getMetadata($options);
//
//    $repository = $this->resourceManager->getRepository($metadata->getApplicationName(), $metadata->getName());
//
//    if ($request->getMethod() === 'POST') {
//        if ($configuration->isCsrfProtectionEnabled() && !$this->isCsrfTokenValid('list_data', $request->get('_csrf_token'))) {
//            throw new HttpException(Response::HTTP_FORBIDDEN, 'Invalid csrf token.');
//        }
//        $this->sortingManager->handleSort($request, $configuration, $repository);
//        return new JsonResponse();
//    }
//}
//
//public function createTemplateData($options, ViewData $data, TemplateData $templateData)
//{
//    $requestConfiguration = $this->util->getRequestConfiguration($options);
//
//    $columns = $this->util->mergeConfigArray([
//        $options['columns'],
//        $this->util->getViewerOption('columns', $requestConfiguration)
//    ]);
//
//    $childrenProperty = $this->util->mergeConfig([
//        $options['children_property'],
//        $this->util->getViewerOption('children_property', $requestConfiguration)
//    ]);
//
//    $positionProperty = $this->util->mergeConfig([
//        $options['position_property'],
//        $this->util->getViewerOption('position_property', $requestConfiguration)
//    ]);
//
//    $token = $this->tokenManager->getToken('list_data');
//    $data['token'] = $token->getValue();
//    $data['resources'] = $this->columnManager->createResourcesViewData($columns, $this->resources, $childrenProperty, $positionProperty);
//}
//
//public function getResponse($options, Request $request, ViewData $viewData, TemplateData $templateData): Response
//{
//    return new JsonResponse($viewData->normalize());
//}
//
//public function configureOptions(OptionsResolver $optionsResolver)
//{
//    $optionsResolver->setDefaults([
//        'columns' => [],
//        'children_property' => null,
//        'position_property' => null,
//    ]);
//
//    $optionsResolver->setRequired('request_configuration');
//}
//
//protected function isCsrfTokenValid(string $id, ?string $token): bool
//{
//    return $this->tokenManager->isTokenValid(new CsrfToken($id, $token));
//}
//
//public function handleSort(Request $request, RequestConfiguration $configuration, EntityRepository $repository)
//{
//    $parentProperty = $this->getParentProperty($configuration);
//    $positionProperty = $this->getPositionProperty($configuration);
//    $propertyAccessor = PropertyAccess::createPropertyAccessor();
//
//    $content = json_decode($request->getContent(), true);
//    $parent = $content['parent'] === null ? null: $repository->find($content['parent']);
//    foreach($content['items'] as $position => $id) {
//        $item = $repository->find($id);
//        if($parentProperty) {
//            $propertyAccessor->setValue($item, $parentProperty, $parent);
//        }
//        if($positionProperty) {
//            $propertyAccessor->setValue($item, $positionProperty, $position);
//        }
//    }
//
//    $this->em->flush();
//}
//
//private function getParentProperty(RequestConfiguration $configuration)
//{
//    $options = $configuration->getViewerOptions();
//    if(isset($options['parent_property'])) {
//        return $options['parent_property'];
//    }
//    return null;
//}
//
//private function getPositionProperty(RequestConfiguration $configuration)
//{
//    $options = $configuration->getViewerOptions();
//    if(isset($options['position_property'])) {
//        return $options['position_property'];
//    }
//    return null;
//}

