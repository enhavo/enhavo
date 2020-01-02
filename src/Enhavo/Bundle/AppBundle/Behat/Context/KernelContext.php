<?php

namespace Enhavo\Bundle\AppBundle\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Behat\Behat\Context\SnippetAcceptingContext;
use Doctrine\ORM\EntityManagerInterface;

/**
 * DefaultContext.php
 *
 * @since 27/01/16
 * @author gseidel
 */
class KernelContext extends RawMinkContext implements Context, KernelAwareContext, SnippetAcceptingContext
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
     * @return EntityManagerInterface
     */
    public function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
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
