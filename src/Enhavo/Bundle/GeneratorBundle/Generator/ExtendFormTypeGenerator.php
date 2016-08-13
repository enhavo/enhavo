<?php
/**
 * ExtendGenerator.php
 *
 * @since 06/07/15
 * @author gseidel
 */

namespace Enhavo\Bundle\GeneratorBundle\Generator;

use Enhavo\Component\ClassAnalyzer\ClassAnalyzer;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Kernel;
use Memio\Memio\Config\Build;
use Memio\Model\File;
use Memio\Model\Object;
use Memio\Model\Method;
use Memio\Model\Argument;
use Memio\Model\FullyQualifiedName;

class ExtendFormTypeGenerator
{
    /**
     * @var EngineInterface
     */
    protected $templateEngine;

    /**
     * @var Kernel
     */
    protected $kernel;

    public function __construct(EngineInterface $templateEngine, Kernel $kernel)
    {
        $this->templateEngine = $templateEngine;
        $this->kernel = $kernel;
    }

    public function generate($source, $target)
    {
        $sourceForm = $this->resolveFormType($source);
        $targetForm = $this->resolveTargetFormType($target);
        $code = $this->generateCode($sourceForm, $targetForm);
        $this->createFile($targetForm, $code);
    }

    public function resolveFormType($name)
    {
        $parts = preg_split('/:/', $name);
        $bundleName = $parts[0];
        $formType = $parts[1];
        $path = $this->kernel->locateResource(sprintf('@%s/Form/Type/%sType.php', $bundleName, $formType));
        return $path;
    }

    public function resolveTargetFormType($name)
    {
        $parts = preg_split('/:/', $name);
        $bundleName = $parts[0];
        $formType = $parts[1];
        $bundlePath = $this->kernel->locateResource(sprintf('@%s', $bundleName));
        $path = sprintf('%sForm/Type/%sType.php', $bundlePath, $formType);
        return $path;
    }

    public function getClassName($path)
    {
        $srcDir = realpath(sprintf('%s/../src', $this->kernel->getRootDir()));
        $className = substr($path, strlen($srcDir) + 1);
        $className = str_replace('/', '\\', $className);
        $className = str_replace('.php', '', $className);
        return $className;
    }

    public function createFile($filePath, $code)
    {
        $pathInfo = pathinfo($filePath);
        $dirName = $pathInfo['dirname'];
        if(!file_exists($dirName)) {
            mkdir($dirName, 0777, true);
        }
        file_put_contents($filePath, $code);
    }

    public function generateCode($source, $target, $constructor = false)
    {
        $classAnalyzer = new ClassAnalyzer();
        $classAnalyzer->setFile($source);
        $parent = Object::make($classAnalyzer->getClassName());

        $targetClassName = $this->getClassName($target);
        $file = File::make($target);
        $object = Object::make($targetClassName);
        $object->extend($parent);

        $file->addFullyQualifiedName(new FullyQualifiedName($classAnalyzer->getFullClassName()));

        if($constructor) {
            $constructor = $classAnalyzer->getConstructor();
            $method = Method::make('__construct');
            foreach($constructor as $parameter) {
                $type = $parameter[0];
                if($type === null) {
                    $type = 'null';
                }
                $method->addArgument(new Argument($type, $parameter[1]));
                $method->setBody('parent::__construct()');
            }
            $object->addMethod($method);
        }

        $file->setStructure($object);

        $prettyPrinter = Build::prettyPrinter();
        $generatedCode = $prettyPrinter->generateCode($file);
        return $generatedCode;
    }
}