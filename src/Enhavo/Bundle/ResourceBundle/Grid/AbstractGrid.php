<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Grid;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Enhavo\Bundle\ResourceBundle\Batch\Batch;
use Enhavo\Bundle\ResourceBundle\Batch\BatchManager;
use Enhavo\Bundle\ResourceBundle\Collection\CollectionFactory;
use Enhavo\Bundle\ResourceBundle\Collection\CollectionInterface;
use Enhavo\Bundle\ResourceBundle\Column\Column;
use Enhavo\Bundle\ResourceBundle\Column\ColumnManager;
use Enhavo\Bundle\ResourceBundle\Exception\GridException;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Filter\Filter;
use Enhavo\Bundle\ResourceBundle\Filter\FilterManager;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

abstract class AbstractGrid implements GridInterface, ServiceSubscriberInterface
{
    protected array $options;

    protected ContainerInterface $container;

    public function setOptions($options): void
    {
        $this->options = $options;
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public static function getSubscribedServices(): array
    {
        return [
            ActionManager::class,
            ColumnManager::class,
            FilterManager::class,
            BatchManager::class,
            ResourceExpressionLanguage::class,
            ResourceManager::class,
            CollectionFactory::class,
            RouteResolverInterface::class,
            RequestStack::class,
            CsrfTokenManagerInterface::class,
            'router',
        ];
    }

    /**
     * @throws GridException
     *
     * @return Action[]
     */
    protected function createActions($configuration, ?object $resource = null): array
    {
        if (!$this->container->has(ActionManager::class)) {
            throw GridException::missingService(ActionManager::class);
        }

        /** @var ActionManager $actionManager */
        $actionManager = $this->container->get(ActionManager::class);

        return $actionManager->getActions($configuration, $resource);
    }

    /**
     * @throws GridException
     *
     * @return Column[]
     */
    protected function createColumns($configuration): array
    {
        if (!$this->container->has(ColumnManager::class)) {
            throw GridException::missingService(ColumnManager::class);
        }

        /** @var ColumnManager $columnManager */
        $columnManager = $this->container->get(ColumnManager::class);

        return $columnManager->getColumns($configuration);
    }

    /**
     * @throws GridException
     *
     * @return Filter[]
     */
    protected function createFilters($configuration): array
    {
        if (!$this->container->has(FilterManager::class)) {
            throw GridException::missingService(FilterManager::class);
        }

        /** @var FilterManager $filterManager */
        $filterManager = $this->container->get(FilterManager::class);

        return $filterManager->getFilters($configuration);
    }

    /**
     * @throws GridException
     *
     * @return Batch[]
     */
    protected function createBatches($configuration, EntityRepository $entityRepository): array
    {
        if (!$this->container->has(BatchManager::class)) {
            throw GridException::missingService(BatchManager::class);
        }

        /** @var BatchManager $batchManager */
        $batchManager = $this->container->get(BatchManager::class);

        return $batchManager->getBatches($configuration, $entityRepository);
    }

    protected function getRepository($name): EntityRepository
    {
        if (!$this->container->has(ResourceManager::class)) {
            throw GridException::missingService(ResourceManager::class);
        }

        /** @var ResourceManager $actionManager */
        $manager = $this->container->get(ResourceManager::class);

        return $manager->getRepository($name);
    }

    /**
     * @throws GridException
     */
    protected function createCollection(EntityRepository $repository, array $filters, array $columns, array $routes, array $configuration, string $resourceName): CollectionInterface
    {
        if (!$this->container->has(CollectionFactory::class)) {
            throw GridException::missingService(CollectionFactory::class);
        }

        /** @var CollectionFactory $collectionFactory */
        $collectionFactory = $this->container->get(CollectionFactory::class);

        return $collectionFactory->create($repository, $filters, $columns, $routes, $configuration, $resourceName);
    }

    protected function evaluate($expression, $parameters = [])
    {
        if (!$this->container->has(ResourceExpressionLanguage::class)) {
            throw GridException::missingService(ResourceExpressionLanguage::class);
        }

        /** @var ResourceExpressionLanguage $expressionLanguage */
        $expressionLanguage = $this->container->get(ResourceExpressionLanguage::class);

        return $expressionLanguage->evaluate($expression, $parameters);
    }

    protected function evaluateArray($array, $parameters = []): array
    {
        $newArray = [];
        foreach ($array as $key => $item) {
            $newArray[$key] = $this->evaluate($item, $parameters);
        }

        return $newArray;
    }

    protected function resolveRoute(string $name, array $context = []): ?string
    {
        if (!$this->container->has(RouteResolverInterface::class)) {
            throw GridException::missingService(RouteResolverInterface::class);
        }

        /** @var RouteResolverInterface $routeResolver */
        $routeResolver = $this->container->get(RouteResolverInterface::class);

        return $routeResolver->getRoute($name, $context);
    }

    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }

    protected function getRequest(): ?Request
    {
        if (!$this->container->get(RequestStack::class)) {
            throw GridException::missingService(RequestStack::class);
        }

        /** @var RequestStack $requestStack */
        $requestStack = $this->container->get(RequestStack::class);

        return $requestStack->getMainRequest();
    }

    protected function getCsrfTokenManager(): CsrfTokenManagerInterface
    {
        if (!$this->container->get(CsrfTokenManagerInterface::class)) {
            throw GridException::missingService(CsrfTokenManagerInterface::class);
        }

        return $this->container->get(CsrfTokenManagerInterface::class);
    }
}
