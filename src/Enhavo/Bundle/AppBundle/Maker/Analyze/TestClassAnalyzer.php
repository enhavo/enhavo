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

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class TestClassAnalyzer
{
    private string $path;

    private ?array $ast;

    /** @var ConstructArgument[] */
    private array $constructArguments = [];

    /** @var UseItem[] */
    private array $uses = [];

    private ?string $testNamespace = null;

    public function __construct(private string $fqcn)
    {
        $parser = (new ParserFactory())->createForNewestSupportedVersion();

        $reflector = new \ReflectionClass($this->fqcn);
        $this->path = $reflector->getFileName();
        $this->ast = $parser->parse(file_get_contents($this->path));

        $constructArgumentFinder = new ConstructArgumentFinder();
        $traverser = new NodeTraverser();
        $traverser->addVisitor($constructArgumentFinder);
        $traverser->traverse($this->ast);
        $this->constructArguments = $constructArgumentFinder->getConstructArguments();

        $useItemFinder = new UseItemFinder($this->constructArguments);
        $traverser = new NodeTraverser();
        $traverser->addVisitor($useItemFinder);
        $traverser->traverse($this->ast);
        $this->uses = $useItemFinder->getUses();
    }

    public function getConstructArguments(): array
    {
        return $this->constructArguments;
    }

    public function getUses(): array
    {
        return $this->uses;
    }

    public function getShortName()
    {
        $reflector = new \ReflectionClass($this->fqcn);

        return $reflector->getShortName();
    }
}
