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

    /**
     * @var string
     */
    private $underscoreName;


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

    public function getUnderscoreName()
    {
        return $this->underscoreName;
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
        $this->setUnderscoreName($name);
    }

    private function setUnderscoreName($name)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $name, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        $this->underscoreName = implode('_', $ret);
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