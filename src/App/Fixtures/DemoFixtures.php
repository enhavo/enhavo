<?php

namespace App\Fixtures;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use InvalidArgumentException;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader as DataFixturesLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author gseidel
 */
class DemoFixtures
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(EntityManagerInterface $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function loadFixtures()
    {
        $path = sprintf('%s/Fixtures', __DIR__);
        $loader = new DataFixturesLoader($this->container);
        $loader->loadFromDirectory($path);
        $fixtures = $loader->getFixtures();
        if (!$fixtures) {
            throw new InvalidArgumentException(
                sprintf('Could not find any fixtures to load in: %s', $path)
            );
        }

        $purger = new ORMPurger($this->em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);

        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($fixtures);
    }
}
