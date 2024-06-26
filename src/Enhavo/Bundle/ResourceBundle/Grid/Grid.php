<?php

namespace Enhavo\Bundle\ResourceBundle\Grid;

use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Grid extends AbstractGrid
{
    public function getActionViewData(ResourceInterface $resource = null, array $configuration = []): array
    {
        $actions = $this->getActions($this->mergeArray($this->options['actions'], $configuration));
        $data = [];
        foreach($actions as $action) {
            $data[] = $action->createViewData($resource);
        }
        return $data;
    }

    public function getActionSecondaryViewData(ResourceInterface $resource = null, array $configuration = []): array
    {
        $actions = $this->getActions($this->options['actions_secondary']);
        $data = [];
        foreach($actions as $action) {
            $data[] = $action->createViewData($resource);
        }
        return $data;
    }

    public function getColumnsViewData(array $configuration = []): array
    {
        $columns = $this->getColumns($this->options['columns']);
        $data = [];
        foreach($columns as $column) {
            $data[] = $column->createViewData();
        }
        return $data;
    }

    public function getColumnsResourceViewData(ResourceInterface $resource, array $configuration = []): array
    {
        $columns = $this->getColumns($this->options['columns']);
        $data = [];
        foreach($columns as $column) {
            $data[] = $column->createResourceViewData($resource);
        }
        return $data;
    }

    public function getFiltersViewData(array $configuration = []): array
    {
        $filters = $this->getFilters($this->options['filters']);
        $data = [];
        foreach($filters as $filter) {
            $data[] = $filter->createViewData();
        }
        return $data;
    }

    public function getBatchesViewData(array $configuration = []): array
    {
        $filters = $this->getFilters($this->options['batches']);
        $data = [];
        foreach($filters as $filter) {
            $data[] = $filter->createViewData();
        }
        return $data;
    }

    public function getSorting()
    {
        return$this->options['sorting'];
    }

    public function getRepository(): string
    {
        return$this->options['repository'];
    }

    public function getRepositoryMethods(): string
    {
        return $this->options['repository_method'];
    }

    public function getRepositoryArguments(): array
    {
        return $this->options['repository_arguments'];
    }

    public function createTabViewData(array $tabs, ?string $translationDomain = null): array
    {
        $data = [];
        foreach ($tabs as $key => $tab) {
            $tabData = [];
            $tabData['label'] = $this->translator->trans($tab['label'], [], isset($tab['translation_domain']) ? $tab['translation_domain'] : $translationDomain);
            $tabData['key'] = $key;
            $tabData['fullWidth'] = isset($tab['full_width']) && $tab['full_width'] ? true : false;
            $tabData['template'] = $tab['template'];
            $data[] = $tabData;
        }
        return $data;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'actions' => [],
            'actions_secondary' => [],
            'columns' => [],
            'filters' => [],
            'batches' => [],
            'sorting' => null,
            'repository_method' => 'findPaginated',
            'repository_arguments' => [],
        ]);

        $resolver->setRequired('resource');
    }

    private function mergeArray($array1, $array2): array
    {
        foreach ($array2 as $key => $value) {
            $array1[$key] = $value;
        }

        return $array1;
    }
}
