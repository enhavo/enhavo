<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-10
 * Time: 13:31
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Metadata;


class Reference
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var string
     */
    private $nameField;

    /**
     * @var string
     */
    private $idField;

    /**
     * Reference constructor.
     * @param string $property
     * @param string $nameField
     * @param string $idField
     */
    public function __construct(string $property, string $nameField, string $idField)
    {
        $this->property = $property;
        $this->nameField = $nameField;
        $this->idField = $idField;
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function getNameField(): string
    {
        return $this->nameField;
    }

    /**
     * @return string
     */
    public function getIdField(): string
    {
        return $this->idField;
    }
}
