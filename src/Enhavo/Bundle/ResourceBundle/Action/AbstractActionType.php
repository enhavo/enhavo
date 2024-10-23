<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:15
 */

namespace Enhavo\Bundle\ResourceBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\Type\BaseActionType;
use Enhavo\Component\Type\AbstractType;

/**
 * @property ActionTypeInterface $parent
 */
abstract class AbstractActionType extends AbstractType implements ActionTypeInterface
{
    public function createViewData(array $options, Data $data, object $resource = null): void
    {

    }

    public function getPermission(array $options, object $resource = null): mixed
    {
        return $this->parent->getPermission($options, $resource);
    }

    public function isEnabled(array $options, object $resource = null): bool
    {
        return $this->parent->isEnabled($options, $resource);
    }

    public function getLabel(array $options): string
    {
        return $this->parent->getPermission($options);
    }

    public static function getParentType(): ?string
    {
        return BaseActionType::class;
    }
}
