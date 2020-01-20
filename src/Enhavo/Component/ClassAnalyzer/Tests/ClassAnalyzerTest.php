<?php

namespace Enhavo\Component\ClassAnalyzer\Tests;

use Enhavo\Component\ClassAnalyzer\ClassAnalyzer;
use PHPUnit\Framework\TestCase;

class ClassAnalyzerTest extends TestCase
{
    function testInitialize()
    {
        $analyzer = new ClassAnalyzer();
        $this->assertInstanceOf('Enhavo\Component\ClassAnalyzer\ClassAnalyzer', $analyzer);
    }

    function testGetFullClassName()
    {
        $analyzer = new ClassAnalyzer();
        $analyzer->setCode($this->getCodeExampleOne());
        $this->assertEquals('Acme\Demo\Foo', $analyzer->getFullClassName());
    }

    function testGetClassName()
    {
        $analyzer = new ClassAnalyzer();
        $analyzer->setCode($this->getCodeExampleOne());
        $this->assertEquals('Foo', $analyzer->getClassName());
    }

    function testGetNamespace()
    {
        $analyzer = new ClassAnalyzer();
        $analyzer->setCode($this->getCodeExampleOne());
        $this->assertEquals('Acme\Demo', $analyzer->getNamespace());
    }

    /**
     * @expectedException \Enhavo\Component\ClassAnalyzer\NoSourceException
     */
    function testThrowExceptionIfNoSourceWasSet()
    {
        $analyzer = new ClassAnalyzer();
        $analyzer->getNamespace();
    }

    /**
     * @expectedException \Enhavo\Component\ClassAnalyzer\NoSourceException
     */
    function testThrowExceptionIfFileDoesNotExists()
    {
        $analyzer = new ClassAnalyzer();
        $analyzer->setFile('ThisFileDoesHopefullyNotExist');
        $analyzer->getNamespace();
    }

    public function testGetUses()
    {
        $analyzer = new ClassAnalyzer();
        $analyzer->setCode($this->getCodeExampleOne());
        $this->assertSame(array(
            'MyClass' => 'Acme\Demo\Package\MyClass',
            'AnotherClass' => 'Acme\Demo\Package\AnotherClass'
        ), $analyzer->getUses());
    }

    public function testGetUsesInDifferentFormat()
    {
        $analyzer = new ClassAnalyzer();
        $analyzer->setCode($this->getCodeExampleTwo());
        $this->assertSame(array(
            'Demo' => '\Acme\Demo',
            'Package' => 'Package'
        ), $analyzer->getUses());
    }

    public function testGetConstructor()
    {
        $analyzer = new ClassAnalyzer();
        $analyzer->setCode($this->getCodeExampleOne());
        $this->assertSame(array(
            array('\DateTime', 'date'),
            array('MyClass', 'myClass'),
            array('AnotherClass', 'anotherClass'),
            array(null, 'bar')
        ), $analyzer->getConstructor());
    }

    public function testGetConstructorInDifferentFormat()
    {
        $analyzer = new ClassAnalyzer();
        $analyzer->setCode($this->getCodeExampleTwo());
        $this->assertSame(array(
            array('\DateTime', 'date'),
            array('MyClass', 'myClass'),
            array('AnotherClass', 'anotherClass'),
            array(null, 'bar')
        ), $analyzer->getConstructor());
    }

    public function testGetFunctions()
    {
        $analyzer = new ClassAnalyzer();
        $analyzer->setCode($this->getCodeExampleOne());
        $this->assertSame(array(
            '__construct', 'bar', 'hello'
        ), $analyzer->getFunctions());
    }

    public function testGetFunctionsInDifferentFormat()
    {
        $analyzer = new ClassAnalyzer();
        $analyzer->setCode($this->getCodeExampleTwo());
        $this->assertSame(array(
            'Foo', 'bar', 'hello'
        ), $analyzer->getFunctions());
    }

    public function testGetFunctionParameters()
    {
        $analyzer = new ClassAnalyzer();
        $analyzer->setCode($this->getCodeExampleOne());

        $this->assertSame(array(), $analyzer->getFunctionParameters('bar'));
        $this->assertSame(array(
            array(null, 'world')
        ), $analyzer->getFunctionParameters('hello'));
    }

    public function testGetFunctionParametersInDifferentFormat()
    {
        $analyzer = new ClassAnalyzer();
        $analyzer->setCode($this->getCodeExampleTwo());

        $this->assertSame(array(), $analyzer->getFunctionParameters('bar'));
        $this->assertSame(array(
            array(null, 'world')
        ), $analyzer->getFunctionParameters('hello'));
    }

    function getCodeExampleOne()
    {
        return '<?php
            namespace Acme\Demo;

            use Acme\Demo\Package\MyClass;
            use Acme\Demo\Package\AnotherClass;

            class Foo {
                public function __construct(\DateTime $date, MyClass $myClass, AnotherClass $anotherClass, $bar) {

                }

                public function bar() {
                    return "bar";
                }

                public function hello($world) {
                    return "world";
                }
            }
        ';
    }

    function getCodeExampleTwo()
    {
        return '<?php namespace Acme
        use \Acme\Demo ;use Package
        ;
        class  Foo extends Super implement Something {
            static public function Foo( \DateTime $date , MyClass $myClass,AnotherClass $anotherClass,  $bar ) {}
            abstract public function bar(  ) { return "bar";}
            public function hello( $world  ) { return "world";}
        }';
    }
}
