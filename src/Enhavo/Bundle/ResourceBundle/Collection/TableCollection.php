<?php

namespace Enhavo\Bundle\ResourceBundle\Collection;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQueryFactory;
use Enhavo\Bundle\ResourceBundle\Repository\FilterRepositoryInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableCollection extends AbstractCollection
{
    public function __construct(
        private readonly ResourceExpressionLanguage $expressionLanguage,
        private readonly FilterQueryFactory $filterQueryFactory,
        private readonly RequestStack $requestStack,
    )
    {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'limit' => 50,
            'paginated' => true,
            'sorting' => [],
            'criteria' => [],
            'filters' => [],
            'columns' => [],
            'repository_method' => null,
            'repository_arguments' => null,
            'pagination' => true,
            'pagination_steps' => [5, 10, 50, 100, 500]
        ]);
    }

    public function getViewData(array $context = []): array
    {
        return [
            'page' => $this->options['paginated'] ? $context['page'] ?? 1 : false,
            'paginationSteps' => $this->options['pagination_steps'],
            'paginationStep' => $this->options['paginated'] ? $context['limit'] ?? $this->options['limit'] : false,
        ];
    }

    public function getItems(array $context = []): array
    {
        if (count($this->options['filters']) && $this->options['repository_method'] === null && !($this->repository instanceof FilterRepositoryInterface)) {
            throw new \Exception();
        } else if ($this->options['repository_arguments'] !== null && $this->options['repository_method'] === null) {
            throw new \Exception();
        }

        $filterQuery = $this->filterQueryFactory->create($this->repository->getClassName(), $this->filters, $this->options['criteria'], $this->options['sorting'], $this->options['paginated']);

        if ($this->options['repository_method'] !== null) {
            $callable = [$this->repository, $this->options['repository_method']];
            $request = $this->requestStack->getMainRequest();
            $resources = call_user_func_array($callable, $this->getRepositoryArguments($this->options, $filterQuery, $request));
        } else if ($this->repository instanceof FilterRepositoryInterface) {
            $resources = $this->repository->filter($filterQuery);
        } else if ($this->options['paginated']) {
            $paginator = $this->createPaginator($this->repository, $this->options);
            $paginator->setMaxPerPage($context['limit'] ?? $this->options['limit']);
            $paginator->setCurrentPage($context['page'] ?? 1);
            $resources = $paginator->getIterator();
        } else {
            $resources = $this->repository->findBy($this->options['criteria'], $this->options['sorting'], $this->options['limit']);
        }

        return $this->createItems($resources);
    }

    private function createItems(iterable $resources): array
    {
        $items = [];
        foreach ($resources as $resource) {
            $data = [];
            foreach($this->columns as $key => $column) {
                $data[$key] = $column->createResourceViewData($resource);
            }

            $item = new ResourceItem($data, $resource);
            $item['id'] = $resource->getId();
            $items[] = $item;
        }
        return $items;
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

    private function createPaginator(EntityRepository $repository, array $options): Pagerfanta
    {
        $queryBuilder = $repository->createQueryBuilder('o');

        $this->applyCriteria($queryBuilder, $options['criteria']);
        $this->applySorting($queryBuilder, $options['sorting']);

        return $this->getPaginator($queryBuilder);
    }

    private function getPaginator(QueryBuilder $queryBuilder): Pagerfanta
    {
        if (!class_exists(QueryAdapter::class)) {
            throw new \LogicException('You can not use the "paginator" if Pargefanta Doctrine ORM Adapter is not available. Try running "composer require pagerfanta/doctrine-orm-adapter".');
        }

        // Use output walkers option in the query adapter should be false as it affects performance greatly (see sylius/sylius#3775)
        return new Pagerfanta(new QueryAdapter($queryBuilder, false, false));
    }

    private function applyCriteria(QueryBuilder $queryBuilder, array $criteria = []): void
    {
        foreach ($criteria as $property => $value) {
            $name = $this->getPropertyName($property);

            if (null === $value) {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
            } elseif (is_array($value)) {
                $queryBuilder->andWhere($queryBuilder->expr()->in($name, $value));
            } elseif ('' !== $value) {
                $parameter = str_replace('.', '_', $property);
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->eq($name, ':' . $parameter))
                    ->setParameter($parameter, $value)
                ;
            }
        }
    }

    private function applySorting(QueryBuilder $queryBuilder, array $sorting = []): void
    {
        foreach ($sorting as $property => $order) {
            if (!empty($order)) {
                $queryBuilder->addOrderBy($this->getPropertyName($property), $order);
            }
        }
    }

    private function getPropertyName(string $name): string
    {
        if (!str_contains($name, '.')) {
            return 'o' . '.' . $name;
        }

        return $name;
    }
}
