<?php

namespace Enhavo\Bundle\DashboardBundle\Dashboard;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property DashboardWidgetTypeInterface $type
 * @property DashboardWidgetTypeInterface[] $parents
 */
class DashboardWidget extends AbstractContainerType
{
    public function createViewData(): array
    {
        $data = new Data();
        $data->set('key', $this->key);
        foreach($this->parents as $parent) {
            $parent->createViewData($this->options, $data);
        }
        $this->type->createViewData($this->options, $data);
        return $data->normalize();
    }

    public function getPermission(): mixed
    {
        return $this->type->getPermission($this->options);
    }

    public function isEnabled(): bool
    {
        return $this->type->isEnabled($this->options);
    }
}
