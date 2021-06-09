<?php

namespace Enhavo\Bundle\AppBundle\Action;

use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ActionLanguageExpression
{
    /** @var ExpressionLanguage */
    private $expressionLanguage;

    /** @var array */
    private $variables = [];

    /**
     * ActionLanguageExpression constructor.
     * @param ExpressionLanguage $expressionLanguage
     */
    public function __construct(ExpressionLanguage $expressionLanguage)
    {
        $this->expressionLanguage = $expressionLanguage;
    }

    /**
     * @param Expression|string $expression
     * @param array $values
     *
     * @return mixed
     */
    public function evaluate($expression, array $values = [])
    {
        $values = array_merge($this->variables, $values);
        return $this->expressionLanguage->evaluate($expression, $values);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function addVariable(string $key, $value)
    {
        $this->variables[$key] = $value;
    }
}
