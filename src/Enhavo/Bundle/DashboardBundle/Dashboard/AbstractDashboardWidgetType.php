<?php


namespace Enhavo\Bundle\DashboardBundle\Dashboard;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\DashboardBundle\Dashboard\Type\BaseDashboardWidgetType;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @property DashboardWidgetTypeInterface $parent
 */
abstract class AbstractDashboardWidgetType extends AbstractType implements DashboardWidgetTypeInterface
{
    public function createViewData(array $options, Data $data): void
    {

    }

    public function getPermission(array $options): mixed
    {
        return $this->parent->getPermission($options);
    }

    public function isEnabled(array $options): bool
    {
        return $this->parent->isEnabled($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public static function getParentType(): ?string
    {
        return BaseDashboardWidgetType::class;
    }
}
