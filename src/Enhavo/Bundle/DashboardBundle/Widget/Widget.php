<?php


namespace Enhavo\Bundle\DashboardBundle\Widget;


use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Component\Type\AbstractContainerType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;

/**
 * @property WidgetTypeInterface $type
 */
class Widget extends AbstractContainerType
{
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
