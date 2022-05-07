<?php

namespace Enhavo\Bundle\AppBundle\Grid;

use Enhavo\Bundle\AppBundle\Util\ArrayUtil;

class Grid
{
    private array $actions = [];
    private array $secondaryActions = [];
    private array $tabs  = [];
    private array $batches = [];
    private array $columns  = [];

    private ?string $label = null;
    private ?string $formTemplate = null;
    private ?string $formAction = null;
    private ?array $formActionParameters = [];
    private ?array $formThemes = [];

    public function addActions($actions)
    {
        $this->actions = $this->mergeConfigArray([$this->actions, $actions]);
    }

    public function addSecondaryActions($actions)
    {
        $this->secondaryActions = $this->mergeConfigArray([$this->secondaryActions, $actions]);
    }

    public function addTabs($tabs)
    {
        $this->tabs = $this->mergeConfigArray([$this->tabs, $tabs]);
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function getSecondaryActions(): array
    {
        return $this->secondaryActions;
    }

    public function getTabs(): array
    {
        return $this->tabs;
    }

    public function getBatches(): array
    {
        return $this->batches;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): void
    {
        $this->label = $label ?? $this->label;
    }

    public function getFormTemplate(): ?string
    {
        return $this->formTemplate;
    }

    public function setFormTemplate(?string $formTemplate): void
    {
        $this->formTemplate = $formTemplate ?? $this->formTemplate;
    }

    public function getFormAction(): ?string
    {
        return $this->formAction;
    }

    public function setFormAction(?string $formAction): void
    {
        $this->formAction = $formAction ?? $this->formAction;
    }

    public function getFormActionParameters(): ?array
    {
        return $this->formActionParameters;
    }

    public function setFormActionParameters(?array $formActionParameters): void
    {
        $this->formActionParameters = $formActionParameters ?? $this->formActionParameters;
    }

    public function getFormThemes(): ?array
    {
        return $this->formThemes;
    }

    public function addFormThemes(?array $formThemes): void
    {
        if (is_array($formThemes)) {
            foreach ($formThemes as $theme) {
                $this->formThemes[] = $theme;
            }
        }
    }

    private function mergeConfigArray($configs): array
    {
        $data = [];
        foreach($configs as $config) {
            if(is_array($config)) {
                $data = ArrayUtil::merge($data, $config);
            }
        }
        return $data;
    }
}
