<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\ExpressionLanguage;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestResourceExpressionVariableProvider implements ResourceExpressionVariableProviderInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function getVariables(): array
    {
        return ['request' => $this->requestStack->getMainRequest()];
    }
}
