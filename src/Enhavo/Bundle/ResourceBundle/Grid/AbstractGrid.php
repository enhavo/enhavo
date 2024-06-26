<?php

namespace Enhavo\Bundle\ResourceBundle\Grid;

use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Enhavo\Bundle\ResourceBundle\Batch\Batch;
use Enhavo\Bundle\ResourceBundle\Batch\BatchManager;
use Enhavo\Bundle\ResourceBundle\Column\Column;
use Enhavo\Bundle\ResourceBundle\Column\ColumnManager;
use Enhavo\Bundle\ResourceBundle\Exception\GridException;
use Enhavo\Bundle\ResourceBundle\Filter\Filter;
use Enhavo\Bundle\ResourceBundle\Filter\FilterManager;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepositoryInterface;
use Psr\Container\ContainerInterface;
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
        ];
    }

    /**
     * @return Action[]
     * @throws GridException
     */
    protected function getActions($configuration, ResourceInterface $resource = null): array
    {
        if (!$this->container->has(ActionManager::class)) {
            throw GridException::missingService(ActionManager::class);
        }

        /** @var ActionManager $actionManager */
        $actionManager = $this->container->get(ActionManager::class);

        return $actionManager->getActions($configuration, $resource);
    }

    /**
     * @return Column[]
     * @throws GridException
     */
    protected function getColumns($configuration): array
    {
        if (!$this->container->has(ColumnManager::class)) {
            throw GridException::missingService(ColumnManager::class);
        }

        /** @var ColumnManager $columnManager */
        $columnManager = $this->container->get(ColumnManager::class);

        return $columnManager->getColumns($configuration);
    }

    /**
     * @return Filter[]
     * @throws GridException
     */
    protected function getFilters($configuration): array
    {
        if (!$this->container->has(FilterManager::class)) {
            throw GridException::missingService(FilterManager::class);
        }

        /** @var FilterManager $filterManager */
        $filterManager = $this->container->get(FilterManager::class);

        return $filterManager->getFilters($configuration);
    }

    /**
     * @return Batch[]
     * @throws GridException
     */
    protected function getBatches($configuration, EntityRepositoryInterface $entityRepository): array
    {
        if (!$this->container->has(BatchManager::class)) {
            throw GridException::missingService(BatchManager::class);
        }

        /** @var BatchManager $batchManager */
        $batchManager = $this->container->get(BatchManager::class);

        return $batchManager->getBatches($configuration, $entityRepository);
    }
}
