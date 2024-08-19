<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-11
 * Time: 02:15
 */

namespace Enhavo\Bundle\AppBundle\Toolbar;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Toolbar\Type\BaseToolbarWidgetType;
use Enhavo\Component\Type\AbstractType;


/**
 * @property ToolbarWidgetTypeInterface $parent
 */
abstract class AbstractToolbarWidgetType extends AbstractType implements ToolbarWidgetTypeInterface
{
    public function isEnabled(array $options): bool
    {
        return $this->parent->isEnabled($options);
    }

    public function getPermission(array $options): mixed
    {
        return $this->parent->getPermission($options);
    }

    public function createViewData(array $options, Data $data): void
    {

    }

    public static function getParentType(): ?string
    {
        return BaseToolbarWidgetType::class;
    }
}
