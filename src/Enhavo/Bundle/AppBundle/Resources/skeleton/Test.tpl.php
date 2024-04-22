<?= "<?php\n"; ?>

namespace <?= $classResolver->getNamespace($fqcn); ?>;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use <?= $fqcn; ?>;
<?php foreach ($classAnalyzer->getUses() as $useItem) {
    echo 'use ' . $useItem->getName() . ";\n";
} ?>

class <?= $classResolver->getClassName($fqcn); ?> extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new <?= $classAnalyzer->getShortName(); ?>Dependencies;
<?php foreach ($classAnalyzer->getConstructArguments() as $argument) {

    if ($argument->isInt()) {
        $value = '0';
    } else if ($argument->isFloat()) {
        $value = '0.0';
    } else if ($argument->isBool()) {
        $value = 'false';
    } else if ($argument->isObject()) {
        $value = 'new object';
    } else if ($argument->isString()) {
        $value = '""';
    } else if ($argument->isArray()) {
        $value = '[]';
    } else if ($argument->isInterface()) {
        $value = sprintf('$this->getMockBuilder(%s::class)->getMock()', $argument->getType());
    } else if ($argument->getType()) {
        $value = sprintf('$this->getMockBuilder(%s::class)->disableOriginalConstructor()->getMock()', $argument->getType());
    } else {
        $value = 'null';
    }
    echo sprintf('        $dependencies->%s = %s;', $argument->getName(), $value) . "\n";
} ?>
        return $dependencies;
    }

    public function createInstance(<?= $classAnalyzer->getShortName(); ?>Dependencies $dependencies)
    {
        $instance = new <?= $classAnalyzer->getShortName(); ?>(
<?php foreach ($classAnalyzer->getConstructArguments() as $argument) {
    echo sprintf('            $dependencies->%s,', $argument->getName()) . "\n";
} ?>
        );
        return $instance;
    }

    public function test()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);
    }
}

class <?= $classAnalyzer->getShortName(); ?>Dependencies
{
<?php foreach ($classAnalyzer->getConstructArguments() as $argument) {
    if ($argument->isPrimitive()) {
        echo sprintf('    public %s $%s;', $argument->getType(), $argument->getName()) . "\n";
    } elseif ($argument->getType()) {
        echo sprintf('    public %s|MockObject $%s;', $argument->getType(), $argument->getName()) . "\n";
    } else {
        echo sprintf('    public $%s;', $argument->getName()) . "\n";
    }
} ?>
}
