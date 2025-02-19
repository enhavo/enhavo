<?php

namespace Enhavo\Bundle\ResourceBundle\ExpressionLanguage;

interface ResourceExpressionVariableProviderInterface
{
    /** @return array<string, mixed> */
    public function getVariables(): array;
}
