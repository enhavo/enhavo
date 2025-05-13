<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Util;

class AssociationNode
{
    private string $class;
    private string $field;
    private bool $singleValued;

    public function __construct(string $class, string $field, bool $singleValued)
    {
        $this->class = $class;
        $this->field = $field;
        $this->singleValued = $singleValued;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function isSingleValued(): bool
    {
        return $this->singleValued;
    }
}
