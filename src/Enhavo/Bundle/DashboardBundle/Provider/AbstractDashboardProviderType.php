<?php


namespace Enhavo\Bundle\DashboardBundle\Provider;


use Enhavo\Bundle\DashboardBundle\Provider\Type\DashboardProviderType;
use Enhavo\Component\Type\AbstractType;

abstract class AbstractDashboardProviderType extends AbstractType implements ProviderTypeInterface
{
    abstract public function getData($options);

    public static function getParentType(): ?string
    {
        return DashboardProviderType::class;
    }
}
