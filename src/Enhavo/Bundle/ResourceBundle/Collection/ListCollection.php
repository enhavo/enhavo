<?php

namespace Enhavo\Bundle\ResourceBundle\Collection;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Authorization\Permission;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQueryFactory;
use Enhavo\Bundle\ResourceBundle\Repository\FilterRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class ListCollection extends AbstractCollection
{
    public function __construct(
        private readonly ResourceExpressionLanguage $expressionLanguage,
        private readonly FilterQueryFactory $filterQueryFactory,
        private readonly RequestStack $requestStack,
        private readonly RouterInterface $router,
        private readonly CsrfTokenManager $tokenManager,
        private readonly EntityManagerInterface $em,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    )
    {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'children_property' => null,
            'parent_property' => null,
            'position_property' => true,
            'repository_method' => null,
            'repository_arguments' => null,
            'permission' => Permission::CREATE,
            'csrf_protection' => true,
            'filters' => [],
            'criteria' => [],
            'sorting' => [],
            'component' => 'collection-list',
            'sortable' => false,
            'model' => 'ListCollection',
        ]);
    }

    public function getItems(array $context = []): ResourceItems
    {
        if (count($this->options['filters']) && $this->options['repository_method'] === null && !($this->repository instanceof FilterRepositoryInterface)) {
            throw new \Exception();
        } else if ($this->options['repository_arguments'] !== null && $this->options['repository_method'] === null) {
            throw new \Exception();
        }

        if ($this->options['repository_method'] !== null) {
            $callable = [$this->repository, $this->options['repository_method']];
            $request = $this->requestStack->getMainRequest();
            $filterQuery = $this->createFilterQuery($context);
            $resources = call_user_func_array($callable, $this->getRepositoryArguments($this->options, $filterQuery, $request));
        } else if ($this->repository instanceof FilterRepositoryInterface) {
            $filterQuery = $this->createFilterQuery($context);
            $resources = $this->repository->filter($filterQuery);
        } else {
            $resources = $this->repository->findBy($this->getCriteria($context), $this->options['sorting'], $this->options['limit']);
        }

        $meta = new Data();
        $meta->set('filtered', $this->isFiltered($context));
        $items = new ResourceItems($this->createItems($resources, $context), $meta);
        return $items;
    }

    private function createFilterQuery($context): FilterQuery
    {
        $filterQuery = $this->filterQueryFactory->create(
            $this->repository->getClassName(),
            $this->filters,
            $context['filters'] ?? [],
            $this->columns,
            [],
            $this->getCriteria($context),
            $this->options['sorting'],
        );

        if (isset($context['hydrate']) && $context['hydrate'] === 'id') {
            $filterQuery->setHydrate('id');
        }

        return $filterQuery;
    }

    private function getCriteria($context = [])
    {
        $criteria = [];
        if (!$this->isFiltered($context) && $this->options['parent_property']) {
            $criteria[$this->options['parent_property']] = null;
        }

        foreach ($this->options['criteria'] as $key => $value) {
            $criteria[$key] = $value;
        }

        $criteria = $this->expressionLanguage->evaluateArray($criteria);
        return $criteria;
    }

    private function isFiltered($context = [])
    {
        return isset($context['filters']) && count($context['filters']) > 0;
    }

    private function getRepositoryArguments(array $options, FilterQuery $filterQuery, ?Request $request): array
    {
        if ($options['repository_arguments'] === null) {
            return [];
        }

        $arguments = [];
        foreach ($options['repository_arguments'] as $key => $value) {
            $arguments[$key] = $this->expressionLanguage->evaluate($value, [
                'filterQuery' => $filterQuery,
                'request' => $request,
                'options' => $options,
            ]);
        }
        return $arguments;
    }

    private function createItems(iterable $resources, array $context): array
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $items = [];
        foreach ($resources as $resource) {
            $data = [];

            if ($this->isHydrate('data', $context)) {
                foreach($this->columns as $column) {
                    $data[] = $column->createResourceViewData($resource);
                }
            }

            $item = new ListResourceItem($data, $resource);

            if ($this->isHydrate('id', $context)) {
                $item['id'] = is_array($resource) ? $resource['id'] : $resource->getId();
            }

            if ($this->isHydrate('url', $context)) {
                $item['url'] = $this->generateUrl($resource);
            }

            $item['children'] = [];
            if (!$this->isFiltered($context) && $this->options['children_property']) {
                $children = $propertyAccessor->getValue($resource, $this->options['children_property']);
                if (count($children)) {
                    $item['children'] = $this->createItems($children, $context);
                }
            }

            $items[] = $item;
        }
        return $items;
    }

    private function isHydrate(string $field, array $context): bool
    {
        if (!isset($context['hydrate'])) {
            return true;
        }

        if ($context['hydrate'] === $field) {
            return true;
        }

        return false;
    }

    private function generateUrl(object $resource): ?string
    {
        $route = $this->routes['open'] ?? null;

        if ($route === null) {
            return null;
        }

        return $this->router->generate($route, $this->evaluateArray($this->routes['open_parameters'] ?? [], [
            'resource' => $resource,
            'request' => $this->requestStack->getMainRequest(),
        ]));
    }

    private function evaluateArray($array, $parameters = []): array
    {
        $newArray = [];
        foreach ($array as $key => $item) {
            $newArray[$key] = $this->expressionLanguage->evaluate($item, $parameters);
        }
        return $newArray;
    }

    public function getViewData(array $context = []): array
    {
        $viewData =  [
            'component' => $this->options['component'],
            'model' => $this->options['model'],
            'sortable' => $this->options['sortable'],
            'treeable' => $this->options['parent_property'] !== null && $this->options['children_property'],
        ];

        if ($this->options['csrf_protection']) {
            $viewData['csrfToken'] = $this->tokenManager->getToken('list_data')->getValue();
        }

        return $viewData;
    }

    public function handleAction(string $action, array $payload): void
    {
        if ($this->options['permission'] && !$this->authorizationChecker->isGranted(new Permission($this->resourceName, $this->options['permission']))) {
            throw new HttpException(Response::HTTP_FORBIDDEN);
        }

        if ($this->options['csrf_protection'] &&
            (!isset($payload['csrfToken']) ||
            !$this->tokenManager->isTokenValid(new CsrfToken('list_data', $payload['csrfToken']))))
        {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid csrf token');
        }

        if ($action === 'sort') {
            $this->handleSort($payload);
            return;
        }

        throw new HttpException(Response::HTTP_BAD_REQUEST, 'Action not found');
    }

    private function handleSort(array $payload): void
    {
        $parentProperty = $this->options['parent_property'];
        $positionProperty = $this->options['position_property'];
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $parent = $payload['parent'] === null ? null : $this->repository->find($payload['parent']);
        foreach ($payload['items'] as $position => $id) {
            $item = $this->repository->find($id);
            if ($parentProperty) {
                $propertyAccessor->setValue($item, $parentProperty, $parent);
            }
            if ($positionProperty) {
                $propertyAccessor->setValue($item, $positionProperty, $position);
            }
        }

        $this->em->flush();
    }
}
