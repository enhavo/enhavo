<?php

namespace Enhavo\Bundle\ResourceBundle\Collection;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQueryFactory;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepositoryTrait;
use Enhavo\Bundle\ResourceBundle\Repository\FilterRepositoryInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class TableCollection extends AbstractCollection
{
    use EntityRepositoryTrait;

    public function __construct(
        private readonly ResourceExpressionLanguage $expressionLanguage,
        private readonly FilterQueryFactory $filterQueryFactory,
        private readonly RequestStack $requestStack,
        private readonly RouterInterface $router,
    )
    {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'limit' => 100,
            'paginated' => true,
            'repository_method' => null,
            'repository_arguments' => null,
            'pagination_steps' => [5, 10, 50, 100, 500, 1000],
            'component' => 'collection-table',
            'model' => 'TableCollection',
            'filters' => [],
            'sorting' => [],
            'criteria' => [],
        ]);
    }

    public function getViewData(array $context = []): array
    {
        return [
            'component' => $this->evaluateExpressionLanguage($this->options['component']),
            'model' => $this->evaluateExpressionLanguage($this->options['model']),
            'paginated' => $this->evaluateExpressionLanguage($this->options['paginated']),
            'page' => $this->evaluateExpressionLanguage($this->options['paginated']) ? $context['page'] ?? 1 : false,
            'paginationSteps' => $this->options['pagination_steps'],
            'paginationStep' => $this->evaluateExpressionLanguage($this->options['paginated']) ? $context['limit'] ?? $this->evaluateExpressionLanguage($this->options['limit']) : false,
        ];
    }

    private function evaluateExpressionLanguage($option)
    {
        return $this->expressionLanguage->evaluate($option, [
            'options' => $this->options,
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
        } else if ($this->isPaginated($context)) {
            $resources = $this->createPaginator($this->repository, $this->getCriteria(), $this->options['sorting']);
        } else {
            $resources = $this->repository->findBy($this->getCriteria(), $this->options['sorting'], $this->options['limit']);
        }

        if ($resources instanceof Pagerfanta) {
            $resources->setMaxPerPage($context['limit'] ?? $this->options['limit']);
            $resources->setCurrentPage($context['page'] ?? 1);

            $result = new ResourceItems($this->createItems($resources->getIterator(), $context));
            $result->getMeta()->set('page', $resources->getCurrentPage());
            $result->getMeta()->set('count', $resources->getNbResults());
            $result->getMeta()->set('limit', $resources->getMaxPerPage());
            return $result;
        }

        return new ResourceItems($this->createItems($resources, $context));
    }

    private function createFilterQuery($context): FilterQuery
    {
        $filterQuery = $this->filterQueryFactory->create(
            $this->repository->getClassName(),
            $this->filters,
            $context['filters'] ?? [],
            $this->columns,
            $context['sorting'] ?? [],
            $this->getCriteria(),
            $this->options['sorting'],
            $this->isPaginated($context),
        );

        if (isset($context['hydrate']) && $context['hydrate'] === 'id') {
            $filterQuery->setHydrate('id');
        }

        return $filterQuery;
    }

    private function getCriteria()
    {
        return $this->expressionLanguage->evaluateArray($this->options['criteria']);
    }

    private function createItems(iterable $resources, array $context): array
    {
        $items = [];
        foreach ($resources as $resource) {
            $items[] = $this->createItem($resource, $context);
        }
        return $items;
    }

    protected function createItem($resource, array $context): ResourceItem
    {
        $data = [];

        if ($this->isHydrate('data', $context)) {
            foreach($this->columns as $column) {
                $data[] = $column->createResourceViewData($resource);
            }
        }

        $item = new ResourceItem($data, $resource);

        if ($this->isHydrate('id', $context)) {
            $item['id'] = is_array($resource) ? $resource['id'] : $resource->getId();
        }

        if ($this->isHydrate('url', $context)) {
            $item['url'] = $this->generateUrl($resource);
        }

        return $item;
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

    protected function createPaginator(EntityRepository $repository, array $criteria = [], array $sorting = []): Pagerfanta
    {
        $queryBuilder = $repository->createQueryBuilder('o');

        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    private function isPaginated(array $context)
    {
        if (isset($context['paginated'])) {
            return !!$context['paginated'];
        }

        return $this->options['paginated'];
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

    public function handleAction(string $action, array $payload): void
    {

    }
}
