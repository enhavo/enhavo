<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * @return PropertyNode|null
     */
    public function getProperty(string $property)
    {
        $name = $this->nameTransformer->camelCase($property, true);
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }

        return null;
    }

    public function addProperty(PropertyNode $property)
    {
        $this->properties[$property->getProperty()] = $property;
    }
}
