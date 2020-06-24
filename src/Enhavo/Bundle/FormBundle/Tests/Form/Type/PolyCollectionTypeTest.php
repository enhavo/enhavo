<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 06:57
 */

namespace Enhavo\Bundle\FormBundle\Tests\Type;

use Enhavo\Bundle\FormBundle\Form\Type\PolyCollectionType;
use Enhavo\Bundle\FormBundle\Tests\Form\PreloadExtensionFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PolyCollectionTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        return [
            PreloadExtensionFactory::createPolyCollectionTypeExtension()
        ];
    }

    public function testSubmit()
    {
        $form = $this->factory->create(PolyCollectionType::class, [], [
            'entry_types' => ['first' => FirstType::class, 'second' => SecondType::class],
            'allow_add' => true
        ]);

        $form->submit([
            ['_key' => 'first', 'name' => 'hello'],
            ['_key' => 'second', 'label' => 'world'],
        ]);

        $this->assertEquals([
            ['name' => 'hello'],
            ['label' => 'world']
        ], $form->getData());
    }

    public function testSubmitWithData()
    {
        $form = $this->factory->create(PolyCollectionType::class, [
            ['key' => 'first', 'name' => 'hello'],
            ['key' => 'second', 'label' => 'world'],
        ], [
            'entry_types' => ['first' => FirstType::class, 'second' => SecondType::class],
            'entry_type_resolver' => function($data) { return $data['key']; },
        ]);

        $form->submit([
            ['_key' => 'first', 'name' => 'foo'],
            ['_key' => 'second', 'label' => 'bar'],
        ]);

        $this->assertEquals([
            ['key' => 'first', 'name' => 'foo'],
            ['key' => 'second', 'label' => 'bar']
        ], $form->getData());
    }

    public function testSameTypes()
    {
        $form = $this->factory->create(PolyCollectionType::class, [], [
            'entry_types' => ['first' => ContainerType::class, 'second' => ContainerType::class],
            'entry_types_options' => ['first' => ['type' => FirstType::class], 'second' => ['type' => SecondType::class]],
            'allow_add' => true
        ]);

        $form->submit([
            ['_key' => 'first', 'data' => ['name' => 'hello']],
            ['_key' => 'second', 'data' => ['label' => 'world']],
        ]);

        $this->assertEquals([
            ['data' => ['name' => 'hello']],
            ['data' => ['label' => 'world' ]]
        ], $form->getData());
    }

    public function testWithObjects()
    {
        $first = new First();
        $second = new Second();
        $form = $this->factory->create(PolyCollectionType::class, [$first, $second], [
            'entry_types' => ['first' => FirstType::class, 'second' => SecondType::class],
            'entry_types_options' => ['first' => ['data_class' => First::class], 'second' => ['data_class' => Second::class]],
        ]);

        $form->submit([
            ['_key' => 'first', 'name' => 'foo'],
            ['_key' => 'second', 'label' => 'bar'],
        ]);

        $this->assertEquals('foo', $form->getData()[0]->name);
        $this->assertEquals('bar', $form->getData()[1]->label);
    }

    public function testAddAndDelete()
    {
        $first = new First();
        $second = new Second();
        $form = $this->factory->create(PolyCollectionType::class, [$first, $second], [
            'entry_types' => ['first' => FirstType::class, 'second' => SecondType::class],
            'entry_types_options' => ['first' => ['data_class' => First::class], 'second' => ['data_class' => Second::class]],
            'allow_add' => true,
            'allow_delete' => true
        ]);

        $form->submit([
            0 => ['_key' => 'first', 'name' => 'Bob'],
            2 => ['_key' => 'first', 'name' => 'Alice'],
        ]);

        $this->assertEquals('Bob', $form->getData()[0]->name);
        $this->assertArrayNotHasKey(1, $form->getData());
        $this->assertEquals('Alice', $form->getData()[2]->name);
    }

    public function testView()
    {
        $form = $this->factory->create(PolyCollectionType::class, [], [
            'entry_types' => ['first' => FirstType::class, 'second' => SecondType::class],
            'allow_add' => true
        ]);

        $view = $form->createView();

        $this->assertArrayHasKey('prototypes', $view->vars);
        $this->assertTrue($view->vars['allow_add']);
        $this->assertFalse($view->vars['allow_delete']);
    }

    public function testResolverForUnsupportedType()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->factory->create(PolyCollectionType::class, [
            ['key' => 'first', 'name' => 'hello'],
            ['key' => 'second', 'label' => 'world'],
        ], [
            'entry_types' => ['first' => FirstType::class, 'second' => SecondType::class],
        ]);
    }

    public function testResolverForNotMatchedDataClassType()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->factory->create(PolyCollectionType::class, [new First(), new Second()], [
            'entry_types' => ['first' => FirstType::class, 'second' => SecondType::class],
        ]);
    }

    public function testSubmitWithMissingTypeField()
    {
        $this->expectException(InvalidArgumentException::class);
        $form = $this->factory->create(PolyCollectionType::class, null, [
            'entry_types' => ['first' => FirstType::class],
            'allow_add' => true
        ]);

        $form->submit([
            0 => ['name' => 'Bob'],
        ]);
    }

    public function testSubmitWithWrongType()
    {
        $this->expectException(UnexpectedTypeException::class);
        $form = $this->factory->create(PolyCollectionType::class, null, [
            'entry_types' => ['first' => FirstType::class]
        ]);

        $form->submit('something is wrong');
    }

    public function testSubmitWithEmptyData()
    {
        $form = $this->factory->create(PolyCollectionType::class, null, [
            'entry_types' => ['first' => FirstType::class]
        ]);

        $form->submit(null);

        $this->assertEquals([], $form->getData());
    }

    public function testSetData()
    {
        $this->expectException(UnexpectedTypeException::class);
        $form = $this->factory->create(PolyCollectionType::class, null, [
            'entry_types' => ['first' => FirstType::class]
        ]);
        $form->setData('something is wrong');
    }

    public function testEmptyEntryTypes()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->create(PolyCollectionType::class, null, [
            'entry_types' => []
        ]);
    }

    public function testNestedTypes()
    {
        $form = $this->factory->create(PolyCollectionType::class, null, [
            'entry_types' => [
                'nested' => NestedType::class,
                'first' => FirstType::class,
                'second' => SecondType::class
            ],
            'allow_add' => true,
            'allow_delete' => true,
            'prototype_storage' => 'test'
        ]);

        $view = $form->createView();

        $this->assertCount(3, $view->vars['prototypes']);
        $this->assertArrayHasKey('nested', $view->vars['prototypes']);
        $this->assertEquals('test', $view->vars['prototype_storage']);

        $form->submit([
            ['_key' => 'first', 'name' => 'Bob1'],
            ['_key' => 'second', 'label' => 'Alice1'],
            ['_key' => 'nested', 'children' => [
                ['_key' => 'first', 'name' => 'Bob2'],
                ['_key' => 'second', 'label' => 'Alice2'],
            ]],
        ]);

        $this->assertEquals([
            ['name' => 'Bob1'],
            ['label' => 'Alice1'],
            ['children' => [
                ['name' => 'Bob2'],
                ['label' => 'Alice2'],
            ]],
        ], $form->getData());
    }

    public function testBuildView()
    {
        $form = $this->factory->create(NestedPathFirstType::class, [
            'children' => [['textarea' => 'Test']]
        ]);

        $view = $form->createView();

        $this->assertArrayHasKey('data-nested-root', $view->vars['attr']);

        $this->assertArrayNotHasKey('data-nested-path', $view->children['text']->vars['attr']);

        $this->assertArrayHasKey('data-nested-path', $view->children['children']->vars['attr']);
        $this->assertEquals('children', $view->children['children']->vars['attr']['data-nested-path']);

        $this->assertArrayHasKey('data-nested-path', $view->children['children'][0]->vars['attr']);
        $this->assertEquals('0', $view->children['children'][0]->vars['attr']['data-nested-path']);

        $this->assertArrayHasKey('data-nested-path', $view->children['second']->vars['attr']);
        $this->assertEquals('second', $view->children['second']->vars['attr']['data-nested-path']);
    }
}

class NestedPathFirstType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextType::class);
        $builder->add('second', NestedPathSecondType::class);
        $builder->add('children', CollectionType::class, [
            'entry_type' => NestedPathSecondType::class
        ]);
    }
}

class NestedPathSecondType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('textarea', TextareaType::class);
    }
}

class FirstType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);
    }
}

class SecondType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label', TextType::class);
    }
}

class NestedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('children', PolyCollectionType::class, [
            'entry_types' => ['nested' => NestedType::class, 'first' => FirstType::class, 'second' => Second::class],
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => false,
            'prototype_storage' => 'test'
        ]);
    }
}

class NestedCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('children', CollectionType::class, [
            'entry_type' => NestedCollectionType::class,
            'allow_add' => true,
            'allow_delete' => true
        ]);
    }
}

class ContainerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('data', $options['type']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['type']);
    }
}

class First
{
    public $id;
    public $name;

    /**
     * First constructor.
     * @param $id
     * @param $name
     */
    public function __construct($id = null, $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }
}

class Second
{
    public $id;
    public $label;

    /**
     * Second constructor.
     * @param $id
     * @param $label
     */
    public function __construct($id = null, $label = null)
    {
        $this->id = $id;
        $this->label = $label;
    }
}
