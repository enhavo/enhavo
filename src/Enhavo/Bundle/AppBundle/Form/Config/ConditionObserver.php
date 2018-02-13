<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 13.02.18
 * Time: 01:02
 */

namespace Enhavo\Bundle\AppBundle\Form\Config;

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

    public function __construct(Condition $subject, array $values)
    {
        $this->subject = $subject;
        $this->values = $values;
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
}