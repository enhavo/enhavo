<?php

namespace Enhavo\Bundle\ResourceBundle\Grid;

use Enhavo\Bundle\ResourceBundle\Batch\Batch;
use Enhavo\Bundle\ResourceBundle\Collection\CollectionInterface;
use Enhavo\Bundle\ResourceBundle\Collection\ResourceItems;
use Enhavo\Bundle\ResourceBundle\Collection\TableCollection;
use Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge\ConfigMergeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Grid extends AbstractGrid implements ConfigMergeInterface
{
    private ?array $filters = null;
    private ?array $columns = null;
    private ?array $actions = null;
    private ?array $actionsSecondary = null;

    /** @var Batch[]  */
    private ?array $batches = null;
    private ?CollectionInterface $collection = null;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'actions' => [],
            'actions_secondary' => [],
            'criteria' => [],
            'columns' => [],
            'filters' => [],
            'batches' => [],
            'collection' => [
                'class' => TableCollection::class
            ],
            'component' => 'grid-grid',
            'routes' => function (OptionsResolver $resolver): void {
                $resolver->setDefaults([
                    'list' => $this->resolveRoute('list'),
                    'list_parameters' => [],
                    'batch' => $this->resolveRoute('batch'),
                    'batch_parameters' => [],
                    'open' => $this->resolveRoute('update', ['api' => false]),
                    'open_parameters' => [
                        'id' => 'expr:resource.getId()'
                    ],
                ]);
            },
        ]);

        $resolver->setRequired('resource');
    }

    public static function mergeConfigs($before, $current): array
    {
        $mergeKeys = [
            'actions',
            'actions_secondary',
            'criteria',
            'columns',
            'filters',
            'batches',
            'collection',
        ];

        foreach ($current as $key => $value) {
            if (in_array($key, $mergeKeys)) {
                if (array_key_exists($key, $before) && is_array($before[$key])) {
                    $before[$key] = array_merge($before[$key], $current[$key]);
                } else {
                    $before[$key] = $current[$key];
                }
            } else {
                $before[$key] = $current[$key];
            }
        }

        return $before;
    }

    protected function getFilters(): array
    {
        if ($this->filters !== null) {
            return $this->filters;
        }

        $this->filters = $this->createFilters($this->options['filters']);

        return $this->filters;
    }

    protected function getColumns(): array
    {
        if ($this->columns !== null) {
            return $this->columns;
        }

        $this->columns = $this->createColumns($this->options['columns']);

        return $this->columns;
    }

    protected function getActions(): array
    {
        if ($this->actions !== null) {
            return $this->actions;
        }

        $this->actions = $this->createActions($this->options['actions']);

        return $this->actions;
    }

    protected function getActionsSecondary(): array
    {
        if ($this->actionsSecondary !== null) {
            return $this->actionsSecondary;
        }

        $this->actionsSecondary = $this->createActions($this->options['actions_secondary']);

        return $this->actionsSecondary;
    }

    protected function getBatches(): array
    {
        if ($this->batches !== null) {
            return $this->batches;
        }

        $this->batches = $this->createBatches($this->options['batches'], $this->getRepository($this->getResourceName()));

        return $this->batches;
    }

    protected function getCollection(): CollectionInterface
    {
        if ($this->collection !== null) {
            return $this->collection;
        }

        $this->collection = $this->createCollection($this->getRepository($this->getResourceName()), $this->getFilters(), $this->getColumns(), $this->options['routes'], $this->options['collection']);

        return $this->collection;
    }

    protected function getActionViewData(): array
    {
        $data = [];
        foreach ($this->getActions() as $action) {
            $data[] = $action->createViewData();
        }
        return $data;
    }

    protected function getActionsSecondaryViewData(): array
    {
        $data = [];
        foreach ($this->getActionsSecondary() as $action) {
            $data[] = $action->createViewData();
        }
        return $data;
    }

    protected function getColumnsViewData(): array
    {
        $data = [];
        foreach ($this->getColumns() as $column) {
            $data[] = $column->createColumnViewData();
        }
        return $data;
    }

    protected function getFiltersViewData(): array
    {
        $data = [];
        foreach ($this->getFilters() as $filter) {
            $data[] = $filter->createViewData();
        }
        return $data;
    }

    protected function getBatchesViewData(): array
    {
        $data = [];
        foreach ($this->getBatches() as $batch) {
            $data[] = $batch->createViewData();
        }
        return $data;
    }

    public function getResourceName(): string
    {
        return $this->options['resource'];
    }

    public function getItems(array $context = []): ResourceItems
    {
        return $this->getCollection()->getItems($context);
    }

    public function getViewData(array $context = []): array
    {
        return [
            'actions' => $this->getActionViewData(),
            'actionsSecondary' => $this->getActionsSecondaryViewData(),
            'filters' => $this->getFiltersViewData(),
            'columns' => $this->getColumnsViewData(),
            'batches' => $this->getBatchesViewData(),
            'collection' => $this->getCollection()->getViewData($context),
            'routes' => $this->getRoutes(),
        ];
    }

    private function getRoutes(): array
    {
        $routes = [];

        $routes[] = [
            'key' => 'list',
            'route' => $this->options['routes']['list'],
            'parameters' => $this->evaluateArray($this->options['routes']['list_parameters'], ['request' => $this->getRequest()]),
        ];

        $routes[] = [
            'key' => 'batch',
            'route' => $this->options['routes']['batch'],
            'parameters' => $this->evaluateArray($this->options['routes']['batch_parameters'], ['request' => $this->getRequest()]),
        ];

        return $routes;
    }

    public function getBatch(string $name): ?Batch
    {
        $batches = $this->getBatches();
        if ($batches[$name]) {
            return $batches[$name];
        }

        return null;
    }
}
