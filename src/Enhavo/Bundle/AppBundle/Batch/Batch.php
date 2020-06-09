<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-04-03
 * Time: 22:15
 */

namespace Enhavo\Bundle\AppBundle\Batch;

use Enhavo\Bundle\AppBundle\Exception\BatchExecutionException;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Component\Type\AbstractContainerType;
use Sylius\Component\Resource\Model\ResourceInterface;

class Batch extends AbstractContainerType
{
    /** @var BatchTypeInterface */
    protected $type;

    /** @var BatchTypeInterface[] */
    protected $parents;

    public function createViewData(ResourceInterface $resource = null)
    {
        $data = new ViewData();
        foreach ($this->parents as $parent) {
            $parent->createViewData($this->options, $data, $resource);
        }
        $this->type->createViewData($this->options, $data, $resource);
        return $data->normalize();
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

    /**
     * @param $resources
     * @param ResourceInterface $resource
     * @throws BatchExecutionException
     */
    public function execute($resources, ResourceInterface $resource = null)
    {
        $this->type->execute($this->options, $resources, $resource);
    }
}
