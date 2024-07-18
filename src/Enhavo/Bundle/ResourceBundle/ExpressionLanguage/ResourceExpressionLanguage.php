<?php

namespace Enhavo\Bundle\ResourceBundle\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ResourceExpressionLanguage
{
    private array $variables = [];
    private readonly ExpressionLanguage $expressionLanguage;

    public function __construct(ExpressionLanguage $expressionLanguage = null)
    {
        $this->expressionLanguage = $expressionLanguage ?? new ExpressionLanguage();
    }

    public function evaluate($expression, array $values = []): mixed
    {
        if (str_starts_with($expression, 'expr:')) {
            $values = array_merge($this->variables, $values);
            $expression = trim(substr($expression, 5));
            return $this->expressionLanguage->evaluate($expression, $values);
        }

        return $expression;
    }

    public function addVariable(string $key, $value): void
    {
        $this->variables[$key] = $value;
    }
}
