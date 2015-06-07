<?php
/**
 * AdminLoader.php
 *
 * @since 13/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;


class AdminLoader
{
    /**
     * @var ContainerBuilder
     */
    public $container;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var string
     */
    private $bundleName;

    /**
     * @var string
     */
    private $companyName;

    /**
     * @var string
     */
    private $applicationName;

    /**
     * @var string
     */
    private $class;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function load()
    {
        #$definition = $this->getAdminDefinition();
        #$this->container->setDefinition($this->getAdminDefinitionId(), $definition);
    }

    protected function getAdminDefinitionId()
    {
        return sprintf('%s.admin.%s', $this->getApplicationName(), $this->getEntityName());
    }

    /**
     * @return Definition
     */
    protected function getAdminDefinition()
    {
        $definition = new Definition();
        $definition
            ->setClass($this->getClass())
            ->setArguments(array(
                new Reference('service_container'),
                $this->getCompanyName(),
                $this->getBundleName(),
                $this->getEntityName(),
            ))
            ->addMethodCall('init')
            ->addTag('esperanto_admin.admin');
        return $definition;
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return $this->bundleName;
    }

    /**
     * @param string $bundleName
     */
    public function setBundleName($bundleName)
    {
        $this->bundleName = $bundleName;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param string $entityName
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * @return string
     */
    public function getApplicationName()
    {
        return $this->applicationName;
    }

    /**
     * @param string $applicationName
     */
    public function setApplicationName($applicationName)
    {
        $this->applicationName = $applicationName;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }
}