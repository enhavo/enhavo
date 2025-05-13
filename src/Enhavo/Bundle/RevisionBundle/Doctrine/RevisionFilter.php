<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RevisionBundle\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;

class RevisionFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if ($this->isRevisionInterface($targetEntity)) {
            return '('.$targetTableAlias.'.revisionState = "main" OR '.$targetTableAlias.'.revisionState IS NULL)';
        }

        return '';
    }

    private function isRevisionInterface(ClassMetadata $targetEntity): bool
    {
        if ($targetEntity->reflClass->implementsInterface(RevisionInterface::class)) {
            return true;
        }
        /** @var \ReflectionClass $subClass */
        foreach ($targetEntity->subClasses as $subClass) {
            $reflectionClass = new \ReflectionClass($subClass);
            if ($reflectionClass->implementsInterface(RevisionInterface::class)) {
                return true;
            }
        }

        return false;
    }
}
