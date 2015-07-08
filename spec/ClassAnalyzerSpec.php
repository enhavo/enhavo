<?php

namespace spec\Enhavo\Component\ClassAnalyzer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClassAnalyzerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Enhavo\Component\ClassAnalyzer\ClassAnalyzer');
    }

    function it_should_return_the_full_class_name()
    {
        $this->setCode($this->getCodeExampleOne());
        $this->getFullClassName()->shouldReturn('Acme\Demo\Foo');
    }

    function it_should_return_the_class_name()
    {
        $this->setCode($this->getCodeExampleOne());
        $this->getClassName()->shouldReturn('Foo');
    }

    function it_should_return_the_namespace()
    {
        $this->setCode($this->getCodeExampleOne());
        $this->getNamespace()->shouldReturn('Acme\Demo');
    }

    function it_should_throw_exception_if_no_source_was_set()
    {
        $this->shouldThrow('Enhavo\Component\ClassAnalyzer\NoSourceException')->during('getNamespace');
    }

    function it_should_throw_exception_if_file_does_not_exists()
    {
        $this->setFile('ThisFileDoesHopefullyNotExist');
        $this->shouldThrow('Enhavo\Component\ClassAnalyzer\NoSourceException')->during('getNamespace');
    }

    public function it_should_return_uses_array()
    {
        $this->setCode($this->getCodeExampleOne());
        $this->getUses()->shouldReturn(array(
            'MyClass' => 'Acme\Demo\Package\MyClass',
            'AnotherClass' => 'Acme\Demo\Package\AnotherClass'
        ));
    }

    public function it_should_return_uses_array_with_other_example()
    {
        $this->setCode($this->getCodeExampleTwo());
        $this->getUses()->shouldReturn(array(
            'Demo' => '\Acme\Demo',
            'Package' => 'Package'
        ));
    }

    public function it_should_return_parameter_definition_of_constructor()
    {
        $this->setCode($this->getCodeExampleOne());
        $this->getConstructor()->shouldReturn(array(
            array('\DateTime', '$date'),
            array('MyClass', '$myClass'),
            array('AnotherClass', '$anotherClass'),
            array(null, '$bar')
        ));
    }

    public function it_should_return_parameter_definition_of_constructor_with_other_example()
    {
        $this->setCode($this->getCodeExampleTwo());
        $this->getConstructor()->shouldReturn(array(
            array('\DateTime', '$date'),
            array('MyClass', '$myClass'),
            array('AnotherClass', '$anotherClass'),
            array(null, '$bar')
        ));
    }

    public function it_should_return_a_list_of_functions()
    {
        $this->setCode($this->getCodeExampleOne());
        $this->getFunctions()->shouldReturn(array(
            '__construct', 'bar', 'hello'
        ));
    }

    public function it_should_return_a_list_of_functions_with_other_example()
    {
        $this->setCode($this->getCodeExampleTwo());
        $this->getFunctions()->shouldReturn(array(
            'Foo', 'bar', 'hello'
        ));
    }

    public function it_should_return_the_parameter_definition_of_a_function()
    {
        $this->setCode($this->getCodeExampleOne());
        $this->getFunctionParameters('bar')->shouldReturn(array());
        $this->getFunctionParameters('hello')->shouldReturn(array(
            array(null, '$world')
        ));
    }

    public function it_should_return_the_parameter_definition_of_a_function_with_other_example()
    {
        $this->setCode($this->getCodeExampleTwo());
        $this->getFunctionParameters('bar')->shouldReturn(array());
        $this->getFunctionParameters('hello')->shouldReturn(array(
            array(null, '$world')
        ));
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