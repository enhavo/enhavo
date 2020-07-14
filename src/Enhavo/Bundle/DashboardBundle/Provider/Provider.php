<?php


namespace Enhavo\Bundle\DashboardBundle\Provider;


use Enhavo\Component\Type\AbstractContainerType;

class Provider extends AbstractContainerType
{
    /**
     * @var AbstractDashboardProviderType
     */
    protected $type;

    public function getData()
    {
        return $this->type->getData($this->options);
    }
}
