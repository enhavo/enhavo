<?php

namespace Enhavo\Bundle\ResourceBundle\Action;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ActionLanguageExpression
{
    private array $variables = [];

    public function __construct(
        private readonly ExpressionLanguage $expressionLanguage
    )
    {
    }

    public function evaluate($expression, array $values = []): mixed
    {
        $values = array_merge($this->variables, $values);
        return $this->expressionLanguage->evaluate($expression, $values);
    }

    public function addVariable(string $key, $value): void
    {
        $this->variables[$key] = $value;
    }
}
