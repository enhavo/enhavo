<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

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
        if (isset($this->getTargetClasses()[$targetEntity->reflClass->getName()])) {
            return $targetTableAlias.'.tenant = '.$this->getParameter('tenant');
        }

        return '';
    }

    public function isDetectByInterface(): bool
    {
        return $this->detectByInterface;
    }

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
