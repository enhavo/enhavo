<?php

namespace Enhavo\Bundle\ResourceBundle\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

interface ResourceExpressionVariableProviderInterface
{
    public function getVariableName(): string;

    public function getVariableValue(): mixed;
}
