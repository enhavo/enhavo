<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\MultiTenancyBundle\Filter\TenantDoctrineFilter;
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
     *
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
            $tenant = $this->resolver->getTenant();
            if (null === $tenant) {
                return;
            }

            $this->entityManager->getConfiguration()->addFilter('tenant', TenantDoctrineFilter::class);
            /** @var TenantDoctrineFilter $filter */
            $filter = $this->entityManager->getFilters()->enable('tenant');
            $filter->setTargetClasses($this->resolveTargetClasses());
            $filter->setParameter('tenant', $tenant->getKey());
        }
    }

    private function resolveTargetClasses()
    {
        $result = [];
        // TODO: This is too slow and seriously impacts page performance, therefore it is currently not supported. We should look into caching possibilities if we want to support this.
        //        if ($this->configuration['detect_by_interface']) {
        //            $result = $this->detectByInterface();
        //        }
        foreach ($this->configuration['classes'] as $class) {
            $result[$class] = $class;
            foreach (class_parents($class) as $parentClass) {
                $result[$parentClass] = $parentClass;
            }
        }

        return $result;
    }

    //    private function detectByInterface()
    //    {
    //        $result = [];
    //        $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();
    //        /** @var ClassMetadata $classMetadata */
    //        foreach($metaData as $classMetadata) {
    //            if ($classMetadata->getReflectionClass()->implementsInterface(TenantAwareInterface::class)) {
    //                $result [$classMetadata->rootEntityName] = $classMetadata->rootEntityName;
    //            }
    //        }
    //        return $result;
    //    }
}
