<?php

namespace Enhavo\Bundle\AppBundle\Behat\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author gseidel
 */
trait KernelAwareTrait
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->kernel->getContainer()->get('test.service_container');
    }

    /**
     * @param $id
     * @return object
     */
    public function get($id)
    {
        return $this->getContainer()->get($id);
    }
}
