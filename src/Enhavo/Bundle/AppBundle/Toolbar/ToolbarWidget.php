<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-11
 * Time: 15:24
 */

namespace Enhavo\Bundle\AppBundle\Toolbar;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property ToolbarWidgetTypeInterface[] $parents
 * @property ToolbarWidgetTypeInterface $type
 */
class ToolbarWidget extends AbstractContainerType
{
    public function createViewData(): array
    {
        $data = new Data();
        $data->set('key', $this->key);

        foreach ($this->parents as $parent) {
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
