<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Template\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class TemplateExpressionLanguageEvaluator
{
    public function __construct(
        private readonly ExpressionLanguage $expressionLanguage,
    )
    {
    }

    public function evaluate(mixed $data): mixed
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->evaluate($value);
            }
        } else if (is_string($data)) {
            if (str_starts_with($data, 'expr:')) {
                $data = $this->expressionLanguage->evaluate(substr($data, 5));
            }
        }

        return $data;
    }
}
