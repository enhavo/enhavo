<?php
/**
 * PropertyNode.php
 *
 * @since 23/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\Metadata;

/**
 * Generator.php
 *
 * @since 18/08/18
 * @author gseidel
 */
class Generator
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}