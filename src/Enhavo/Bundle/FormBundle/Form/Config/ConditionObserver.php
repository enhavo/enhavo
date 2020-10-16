<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 13.02.18
 * Time: 01:02
 */

namespace Enhavo\Bundle\FormBundle\Form\Config;

class ConditionObserver
{
    /**
     * @var Condition
     */
    private $subject;

    /**
     * @var array
     */
    private $values;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var string|null
     */
    private $scope;

    public function __construct(Condition $subject, array $values, $operator = Condition::AND, $scope = null)
    {
        $this->subject = $subject;
        $this->values = $values;
        $this->operator = $operator;
        $this->scope = $scope;
    }

    /**
     * @return Condition
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param Condition $subject
     */
    public function setSubject(Condition $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values)
    {
        $this->values = $values;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator(string $operator)
    {
        $this->operator = $operator;
    }

    /**
     * @return string|null
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @param string|null $scope
     */
    public function setScope(?string $scope): void
    {
        $this->scope = $scope;
    }
}
