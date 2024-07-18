<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 04.02.18
 * Time: 17:38
 */

namespace Enhavo\Bundle\ResourceBundle\Filter;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Filter\Type\BaseFilterType;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @property FilterTypeInterface $parent
 */
abstract class AbstractFilterType extends AbstractType implements FilterTypeInterface
{
    public function createViewData($options, Data $data): void
    {

    }

    public function buildQuery($options, FilterQuery $query, mixed $value): void
    {

    }

    public function getPermission($options): mixed
    {
        return $this->parent->getPermission($options);
    }

    public function isEnabled($options): bool
    {
        return $this->parent->isEnabled($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

    }

    public static function getParentType(): ?string
    {
        return BaseFilterType::class;
    }
}
