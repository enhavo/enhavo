<?php

namespace Enhavo\Bundle\ResourceBundle\Input;

use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge\ConfigMergeInterface;
use Enhavo\Bundle\ResourceBundle\Tab\Tab;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Input extends AbstractInput implements ConfigMergeInterface
{
    /** @var Action[]|null  */
    private ?array $actions = null;
    /** @var Tab[]|null  */
    private ?array $tabs = null;
    private ?array $actionsSecondary = null;
    private ?object $resource = null;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'actions' => [],
            'actions_secondary' => [],
            'tabs' => [],
            'form' => null,
            'form_options' => [],
            'factory_method' => 'createNew',
            'factory_arguments' => [],
            'repository_method' => 'find',
            'repository_arguments' => [
                'expr:request.get("id", 0)'
            ],
            'serialization_groups' => ['endpoint', 'endpoint.admin'],
            'validation_groups' => ['default'],
        ]);

        $resolver->setRequired('resource');
    }

    public static function mergeConfigs($before, $current): array
    {
        $mergeKeys = [
            'actions',
            'actions_secondary',
        ];

        foreach ($current as $key => $value) {
            if (in_array($key, $mergeKeys)) {
                if (array_key_exists($key, $before) && is_array($before[$key])) {
                    $before[$key] = array_merge($before[$key], $value);
                } else {
                    $before[$key] = $value;
                }
            } else {
                $before[$key] = $value;
            }
        }

        return $before;
    }

    protected function getActions($resource = null): array
    {
        if ($this->actions !== null) {
            return $this->actions;
        }

        $this->actions = $this->createActions($this->options['actions'], $resource);

        return $this->actions;
    }

    /** @return Tab[] */
    protected function getTabs(): array
    {
        if ($this->tabs !== null) {
            return $this->tabs;
        }

        $this->tabs = $this->createTabs($this->options['tabs']);

        return $this->tabs;
    }

    protected function getActionsSecondary($resource = null): array
    {
        if ($this->actionsSecondary !== null) {
            return $this->actionsSecondary;
        }

        $this->actionsSecondary = $this->createActions($this->options['actions_secondary'], $resource);

        return $this->actionsSecondary;
    }

    protected function getActionViewData(object $resource = null): array
    {
        $data = [];
        foreach ($this->getActions($resource) as $action) {
            $data[] = $action->createViewData($resource);
        }
        return $data;
    }

    protected function getActionsSecondaryViewData(object $resource = null): array
    {
        $data = [];
        foreach ($this->getActionsSecondary($resource) as $action) {
            $data[] = $action->createViewData($resource);
        }
        return $data;
    }

    public function getResourceName(): string
    {
        return $this->options['resource'];
    }

    public function getResource(array $context = []): ?object
    {
        if ($this->resource !== null) {
            return $this->resource;
        }
        $callable = [$this->getRepository($this->getResourceName()), $this->options['repository_method']];
        $arguments = $this->evaluateArray($this->options['repository_arguments'], [
            'request' => $this->getRequest(),
            'context' => $context,
        ]);
        $this->resource = call_user_func_array($callable, $arguments);
        return $this->resource;
    }

    public function setResource(?object $resource): void
    {
        $this->resource = $resource;
    }

    public function createResource(array $context = []): object
    {
        $callable = [$this->getFactory($this->getResourceName()), $this->options['factory_method']];
        $arguments = $this->evaluateArray($this->options['factory_arguments'], [
            'request' => $this->getRequest(),
            'context' => $context,
        ]);
        return call_user_func_array($callable, $arguments);
    }

    protected function getTabsViewData(): array
    {
        $data = [];
        foreach ($this->getTabs() as $key => $tab) {
            $data[$key] = $tab->createViewData($this);
        }
        return $data;
    }

    public function getViewData(object $resource = null, array $context = []): array
    {
        return [
            'actions' => $this->getActionViewData($resource),
            'actionsSecondary' => $this->getActionsSecondaryViewData($resource),
            'tabs' => $this->getTabsViewData(),
            'resource' => $resource && $resource->getId() ? $this->normalize($resource, null, ['groups' => $this->options['serialization_groups']]) : null,
        ];
    }

    public function createForm(mixed $data = null, array $context = []): ?FormInterface
    {
        if ($this->options['form']) {
            $options = [
                'validation_groups' => $this->options['validation_groups'],
            ];

            return $this->container->get('form.factory')->create($this->options['form'], $data, array_merge($options, $this->options['form_options']));
        }

        return null;
    }
}
