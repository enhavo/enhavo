<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Enhavo\Bundle\MultiTenancyBundle\Model\TenantAwareInterface;

class TenantDoctrineFilter extends SQLFilter
{
    /**
     * @var bool
     */
    protected $detectByInterface = false;

    /**
     * @var string[]
     */
    protected $targetClasses = [];

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        $validClass = false;
        if ($this->isDetectByInterface() && $targetEntity->reflClass->implementsInterface(TenantAwareInterface::class)) {
            $validClass = true;
        }

        if (!$validClass) {
            foreach($this->getTargetClasses() as $targetClass) {
                if ($targetClass == $targetEntity->reflClass->getName()) {
                    $validClass = true;
                    break;
                }
            }
        }

        if ($validClass) {
            return $targetTableAlias . '.tenant = ' . $this->getParameter('tenant');
        } else {
            return '';
        }
    }

    /**
     * @return bool
     */
    public function isDetectByInterface(): bool
    {
        return $this->detectByInterface;
    }

    /**
     * @param bool $autodetectByInterface
     */
    public function setDetectByInterface(bool $autodetectByInterface): void
    {
        $this->autodetectByInterface = $autodetectByInterface;
    }

    /**
     * @return string[]
     */
    public function getTargetClasses(): array
    {
        return $this->targetClasses;
    }

    /**
     * @param string[] $targetClasses
     */
    public function setTargetClasses(array $targetClasses): void
    {
        $this->targetClasses = $targetClasses;
    }
}
