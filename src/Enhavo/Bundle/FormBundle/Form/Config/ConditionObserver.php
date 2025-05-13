<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

    public function setSubject(Condition $subject)
    {
        $this->subject = $subject;
    }

    public function getValues(): array
    {
        return $this->values;
    }

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

    public function setOperator(string $operator)
    {
        $this->operator = $operator;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    public function setScope(?string $scope): void
    {
        $this->scope = $scope;
    }
}
