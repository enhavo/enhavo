<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Maker\Analyze;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;

class ConstructArgumentFinder extends NodeVisitorAbstract
{
    /** @var ConstructArgument[] */
    private array $constructArguments = [];

    public function enterNode(Node $node)
    {
        if ($node instanceof ClassMethod && '__construct' == $node->name->toString()) {
            /** @var Node\Param $param */
            foreach ($node->params as $param) {
                $this->constructArguments[] = new ConstructArgument($param->var->name, $param->type->name);
            }
        }
    }

    public function getConstructArguments(): array
    {
        return $this->constructArguments;
    }
}
