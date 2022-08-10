<?php

namespace Enhavo\Bundle\AppBundle\Batch;

use Enhavo\Bundle\AppBundle\Batch\Type\BaseBatchType;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Component\Type\AbstractType;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractBatchType extends AbstractType implements BatchTypeInterface
{
    /** @var $parent self */
    protected $parent;

    /**
     * @inheritdoc
     */
    public function execute(array $options, array $resources, ResourceInterface $resource = null): ?Response
    {
        return $this->parent->execute($options, $resources);
    }

    public function createViewData(array $options, ViewData $data, ResourceInterface $resource = null)
    {

    }

    public static function getParentType(): ?string
    {
        return BaseBatchType::class;
    }

    /**
     * @inheritdoc
     */
    public function getPermission(array $options)
    {
        $this->parent->getPermission($options);
    }

    /**
     * @inheritdoc
     */
    public function isHidden(array $options)
    {
        $this->parent->isHidden($options);
    }
}
