<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-04-03
 * Time: 22:15
 */

namespace Enhavo\Bundle\AppBundle\Batch;

use Enhavo\Bundle\AppBundle\Exception\BatchExecutionException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Batch
{
    /**
     * @var BatchTypeInterface
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    public function __construct(BatchTypeInterface $type, $options)
    {
        $this->type = $type;
        $resolver = new OptionsResolver();
        $this->type->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    /**
     * @return array
     */
    public function createViewData()
    {
        return $this->type->createViewData($this->options);
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
     * @throws BatchExecutionException
     */
    public function execute($resources)
    {
        $this->type->execute($this->options, $resources);
    }
}
