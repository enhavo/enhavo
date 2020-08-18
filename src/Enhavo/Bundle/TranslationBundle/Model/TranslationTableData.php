<?php
/**
 * TranslationTableData.php
 *
 * @since 04/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Model;


use Enhavo\Bundle\TranslationBundle\Metadata\PropertyNode;

class TranslationTableData
{
    /**
     * @var object
     */
    private $entity;

    /**
     * @var PropertyNode
     */
    private $property;

    /**
     * @var array
     */
    private $values;

    /**
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param object $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return PropertyNode
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param PropertyNode $property
     */
    public function setProperty($property)
    {
        $this->property = $property;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param array $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }
}
