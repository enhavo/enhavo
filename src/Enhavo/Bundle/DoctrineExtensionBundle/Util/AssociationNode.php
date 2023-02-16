<?php

namespace Enhavo\Bundle\DoctrineExtensionBundle\Util;

class AssociationNode
{
    private string $class;
    private string $field;
    private bool $singleValued;

    /**
     * @param string $class
     * @param string $field
     * @param bool $singleValued
     */
    public function __construct(string $class, string $field, bool $singleValued)
    {
        $this->class = $class;
        $this->field = $field;
        $this->singleValued = $singleValued;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return bool
     */
    public function isSingleValued(): bool
    {
        return $this->singleValued;
    }
}
