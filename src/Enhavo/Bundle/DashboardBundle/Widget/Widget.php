<?php


namespace Enhavo\Bundle\DashboardBundle\Widget;


use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Component\Type\AbstractContainerType;
use Sylius\Component\Resource\Model\ResourceInterface;

class Widget extends AbstractContainerType
{
    /**
     * @var WidgetTypeInterface
     */
    protected $type;

    public function createViewData(ResourceInterface $resource = null)
    {
        $viewData = new ViewData();
        /** @var WidgetTypeInterface $parent */
        foreach($this->parents as $parent) {
            $parent->createViewData($this->options, $viewData, $resource);
        }
        $this->type->createViewData($this->options, $viewData, $resource);
        return $viewData->normalize();
    }

    /**
     * @return bool
     */
    public function getPermission()
    {
        return $this->type->getPermission($this->options);
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return $this->type->isHidden($this->options);
    }
}
