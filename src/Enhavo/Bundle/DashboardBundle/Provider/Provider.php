<?php


namespace Enhavo\Bundle\DashboardBundle\Provider;


use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property AbstractDashboardProviderType $type
 */
class Provider extends AbstractContainerType implements ProviderInterface
{
    public function getData()
    {
        return $this->type->getData($this->options);
    }
}
