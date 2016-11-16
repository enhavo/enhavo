<?php
/**
 * Property.php
 *
 * @since 05/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Metadata;

use Symfony\Component\PropertyAccess\PropertyPath;

class Property
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $strategy;


    public function __construct(PropertyPath $propertyPath = null)
    {
        $property = (string)$propertyPath;
        $this->setName($property);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $name = preg_replace_callback("/(?:^|_)([a-z])/", function($matches) {
            return strtoupper($matches[1]);
        }, $name);
        $name = lcfirst($name);
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * @param string $strategy
     */
    public function setStrategy($strategy)
    {
        $this->strategy = $strategy;
    }
}