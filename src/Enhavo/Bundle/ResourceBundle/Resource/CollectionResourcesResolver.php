<?php
/**
 * ResourcesResolver.php
 *
 * @since 05/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Resource;

use Enhavo\Bundle\ResourceBundle\Filter\FilterQueryBuilder;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesResolverInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class CollectionResourcesResolver implements ResourcesResolverInterface
{
    public function __construct(
        private FilterQueryBuilder $filterQueryBuilder
    )
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getResources($options, RepositoryInterface $repository)
    {
        if ($repository instanceof EntityRepositoryInterface) {
            $query = $this->filterQueryBuilder->buildQueryFromRequestConfiguration($requestConfiguration);
            if (null !== $repositoryMethod = $requestConfiguration->getRepositoryMethod()) {
                $callable = [$repository, $repositoryMethod];
                $resources = call_user_func_array($callable, array_merge($requestConfiguration->getRepositoryArguments(), [$query]));
            } else {
                $resources = $repository->filter($query);
            }

            return $resources;
        }

        if (null !== $repositoryMethod = $requestConfiguration->getRepositoryMethod()) {
            $callable = [$repository, $repositoryMethod];
            $resources = call_user_func_array($callable, $requestConfiguration->getRepositoryArguments());

            return $resources;
        }

        if (!$requestConfiguration->isPaginated() && !$requestConfiguration->isLimited()) {
            return $repository->findBy($requestConfiguration->getCriteria(), $requestConfiguration->getSorting());
        }

        if (!$requestConfiguration->isPaginated()) {
            return $repository->findBy($requestConfiguration->getCriteria(), $requestConfiguration->getSorting(), $requestConfiguration->getLimit());
        }

        return $repository->createPaginator($requestConfiguration->getCriteria(), $requestConfiguration->getSorting());
    }
}
