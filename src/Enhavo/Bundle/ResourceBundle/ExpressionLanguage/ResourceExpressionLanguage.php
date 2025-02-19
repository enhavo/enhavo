<?php

namespace Enhavo\Bundle\ResourceBundle\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
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

    public function evaluateArray(array $array, array $values = [], $recursive = false): mixed
    {
        foreach ($array as $key => $item) {
            if (is_string($item)) {
                $array[$key] = $this->evaluate($item, $values);
            } else if (is_array($item) && $recursive) {
                $array[$key] = $this->evaluateArray($item, $values, $recursive);
            }
        }

        return $array;
    }

    public function addVariableProvider(ResourceExpressionVariableProviderInterface $provider): void
    {
        foreach ($provider->getVariables() as $key => $value) {
            $this->variables[$key] = $value;
        }
    }

    public function addFunctionProvider(ExpressionFunctionProviderInterface $provider): void
    {
        foreach ($provider->getFunctions() as $function) {
            $this->expressionLanguage->addFunction($function);
        }
    }
}
