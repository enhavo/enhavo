<?php


namespace Enhavo\Bundle\AppBundle\Menu;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Component\Type\AbstractContainerType;

/**
 * @author gseidel
 *
 * @property MenuTypeInterface $type
 * @property MenuTypeInterface[] $parents
 */
class Menu extends AbstractContainerType
{
    public function getPermission()
    {
        return $this->type->getPermission($this->options);
    }

    public function isEnabled()
    {
        return $this->type->isEnabled($this->options);
    }

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
}
