<?php


namespace Enhavo\Bundle\AppBundle\Routing\Metadata;

/**
 * Metadata.php
 *
 * @since 18/08/18
 * @author gseidel
 */
class Metadata
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var Generator[]
     */
    private $generators = [];

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @return Generator[]
     */
    public function getGenerators()
    {
        return $this->generators;
    }

    /**
     * @param Generator[] $generators
     */
    public function setGenerators($generators)
    {
        $this->generators = $generators;
    }
}