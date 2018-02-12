<?php
/**
 * TypeFactory.php
 *
 * @since 12/02/18
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Type;

class TypeFactory
{
    /**
     * @var TypeCollector
     */
    private $collector;

    /**
     * @var string
     */
    private $class;

    public function __construct($class, TypeCollector $collector)
    {
        $this->class = $class;
        $this->collector = $collector;
    }

    /**
     * @param $type
     * @param $options
     *
     * @return object
     */
    public function create($type, $options)
    {
        $type = $this->collector->getType($type);
        $class = new $this->class($type, $options);
        return $class;
    }
}