<?php


namespace Enhavo\Bundle\DashboardBundle\Widget;


use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Component\Type\TypeInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface WidgetTypeInterface extends TypeInterface
{
    /**
     * @param $options array
     * @param ViewData $data
     * @param ResourceInterface $resource
     * @return array
     */
    public function createViewData(array $options, ViewData $data, ResourceInterface $resource = null);

    /**
     * @param array $options
     * @return boolean
     */
    public function getPermission(array $options);

    /**
     * @param array $options
     * @return boolean
     */
    public function isHidden(array $options);
}
