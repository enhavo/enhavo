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
use PhpParser\NodeVisitorAbstract;

class UseItemFinder extends NodeVisitorAbstract
{
    /** @var UseItem[] */
    private array $uses = [];

    public function __construct(
        private readonly array $constructArguments,
    ) {
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\UseItem) {
            $reflect = new \ReflectionClass($node->name->toString());
            foreach ($this->constructArguments as $arguments) {
                if ($arguments->getShortName() === $reflect->getShortName()) {
                    $this->uses[] = new UseItem($node->name->toString());
                    break;
                }
            }
        }
    }

    public function getUses(): array
    {
        return $this->uses;
    }
}
