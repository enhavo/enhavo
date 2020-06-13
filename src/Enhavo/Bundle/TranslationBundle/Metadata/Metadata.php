<?php
/**
 * Metadata.php
 *
 * @since 05/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Metadata;

use Enhavo\Bundle\AppBundle\Util\NameTransformer;

class Metadata extends \Enhavo\Component\Metadata\Metadata
{
    /**
     * @var PropertyNode[]
     */
    private $properties = [];

    /**
     * @var NameTransformer
     */
    private $nameTransformer;

    public function __construct($className)
    {
        parent::__construct($className);
        $this->nameTransformer = new NameTransformer();
    }

    /**
     * @return PropertyNode[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param string $property
     * @return PropertyNode|null
     */
    public function getProperty(string $property)
    {
        $name = $this->nameTransformer->camelCase($property, true);
        if(isset($this->properties[$name])) {
            return $this->properties[$name];
        }
        return null;
    }

    public function addProperty(PropertyNode $property)
    {
        $this->properties[$property->getProperty()] = $property;
    }
}
