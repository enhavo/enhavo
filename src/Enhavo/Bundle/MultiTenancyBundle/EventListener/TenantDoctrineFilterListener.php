<?php

namespace Enhavo\Bundle\MultiTenancyBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Enhavo\Bundle\MultiTenancyBundle\Filter\TenantDoctrineFilter;
use Enhavo\Bundle\MultiTenancyBundle\Model\TenantAwareInterface;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;

class TenantDoctrineFilterListener
{
    /**
     * @var ResolverInterface
     */
    private $resolver;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var array
     */
    private $configuration;

    /**
     * TenantDoctrineFilterListener constructor.
     * @param ResolverInterface $resolver
     * @param EntityManagerInterface $entityManager
     * @param array $configuration
     */
    public function __construct(ResolverInterface $resolver, EntityManagerInterface $entityManager, $configuration)
    {
        $this->resolver = $resolver;
        $this->entityManager = $entityManager;
        $this->configuration = $configuration;
    }

    public function onKernelRequest()
    {
        if ($this->configuration['enabled']) {
            $this->entityManager->getConfiguration()->addFilter('tenant', TenantDoctrineFilter::class);
            /** @var TenantDoctrineFilter $filter */
            $filter = $this->entityManager->getFilters()->enable('tenant');
            $filter->setDetectByInterface($this->configuration['detect_by_interface']);
            $filter->setTargetClasses($this->resolveTargetClasses());
            $filter->setParameter('tenant', $this->resolver->getTenant()->getKey());
        }
    }

    private function resolveTargetClasses()
    {
        $result = [];
        if ($this->configuration['detect_inheritance']) {
            $result = $this->detectInheritance();
        }
        foreach($this->configuration['classes'] as $class) {
            $result[$class] = $class;
        }
        return array_keys($result);
    }

    private function detectInheritance()
    {
        $result = [];
        $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();
        /** @var ClassMetadata $classMetadata */
        foreach($metaData as $classMetadata) {
            if ($classMetadata->getReflectionClass()->implementsInterface(TenantAwareInterface::class)) {
                $result [$classMetadata->rootEntityName] = $classMetadata->rootEntityName;
            }
        }
        return $result;
    }
}
