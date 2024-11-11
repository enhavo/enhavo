<?php

namespace Enhavo\Bundle\RevisionBundle\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;

class RevisionFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if (!$targetEntity->reflClass->implementsInterface(RevisionInterface::class)) {
            return "";
        }

        return '('.$targetTableAlias.'.revisionState = "main" OR '.$targetTableAlias.'.revisionState IS NULL)';
    }
}
