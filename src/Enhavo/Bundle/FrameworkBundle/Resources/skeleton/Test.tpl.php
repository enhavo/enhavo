<?php echo "<?php\n"; ?>

namespace <?php echo $classResolver->getNamespace($fqcn); ?>;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use <?php echo $fqcn; ?>;
<?php foreach ($classAnalyzer->getUses() as $useItem) {
    echo 'use '.$useItem->getName().";\n";
} ?>

class <?php echo $classResolver->getClassName($fqcn); ?> extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new <?php echo $classAnalyzer->getShortName(); ?>Dependencies;
<?php foreach ($classAnalyzer->getConstructArguments() as $argument) {
    if ($argument->isInt()) {
        $value = '0';
    } elseif ($argument->isFloat()) {
        $value = '0.0';
    } elseif ($argument->isBool()) {
        $value = 'false';
    } elseif ($argument->isObject()) {
        $value = 'new object';
    } elseif ($argument->isString()) {
        $value = '""';
    } elseif ($argument->isArray()) {
        $value = '[]';
    } elseif ($argument->isInterface()) {
        $value = sprintf('$this->getMockBuilder(%s::class)->getMock()', $argument->getType());
    } elseif ($argument->getType()) {
        $value = sprintf('$this->getMockBuilder(%s::class)->disableOriginalConstructor()->getMock()', $argument->getType());
    } else {
        $value = 'null';
    }
    echo sprintf('        $dependencies->%s = %s;', $argument->getName(), $value)."\n";
} ?>
        return $dependencies;
    }

    public function createInstance(<?php echo $classAnalyzer->getShortName(); ?>Dependencies $dependencies)
    {
        $instance = new <?php echo $classAnalyzer->getShortName(); ?>(
<?php foreach ($classAnalyzer->getConstructArguments() as $argument) {
    echo sprintf('            $dependencies->%s,', $argument->getName())."\n";
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

class <?php echo $classAnalyzer->getShortName(); ?>Dependencies
{
<?php foreach ($classAnalyzer->getConstructArguments() as $argument) {
    if ($argument->isPrimitive()) {
        echo sprintf('    public %s $%s;', $argument->getType(), $argument->getName())."\n";
    } elseif ($argument->getType()) {
        echo sprintf('    public %s|MockObject $%s;', $argument->getType(), $argument->getName())."\n";
    } else {
        echo sprintf('    public $%s;', $argument->getName())."\n";
    }
} ?>
}
