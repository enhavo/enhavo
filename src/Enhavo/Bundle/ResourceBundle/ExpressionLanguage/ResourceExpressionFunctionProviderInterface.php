<?php

namespace Enhavo\Bundle\ResourceBundle\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

interface ResourceExpressionFunctionProviderInterface
{
    public function getFunction(): ExpressionFunction;
}
