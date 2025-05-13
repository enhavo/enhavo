<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Tests\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\FormBundle\Form\Type\PolyCollectionType;
use Enhavo\Bundle\FormBundle\Tests\Form\PreloadExtensionFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PolyCollectionTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        return [
            PreloadExtensionFactory::createPolyCollectionTypeExtension(),
        ];
    }

    public function testSubmit()
    {
        $form = $this->factory->create(PolyCollectionType::class, [], [
            'entry_types' => ['first' => FirstType::class, 'second' => SecondType::class],
            'allow_add' => true,
        ]);

        $form->submit([
            ['_key' => 'first', 'name' => 'hello'],
            ['_key' => 'second', 'label' => 'world'],
        ]);

        $this->assertEquals([
            ['name' => 'hello'],
            ['label' => 'world'],
        ], $form->getData());
    }

    public function testSubmitWithData()
    {
        $form = $this->factory->create(PolyCollectionType::class, [
            ['key' => 'first', 'name' => 'hello'],
            ['key' => 'second', 'label' => 'world'],
        ], [
            'entry_types' => ['first' => FirstType::class, 'second' => SecondType::class],
            'entry_type_resolver' => function ($data) { return $data['key']; },
        ]);

        $form->submit([
            ['_key' => 'first', 'name' => 'foo'],
            ['_key' => 'second', 'label' => 'bar'],
        ]);

        $this->assertEquals([
            ['key' => 'first', 'name' => 'foo'],
            ['key' => 'second', 'label' => 'bar'],
        ], $form->getData());
    }

    public function testSameTypes()
    {
        $form = $this->factory->create(PolyCollectionType::class, [], [
            'entry_types' => ['first' => ContainerDataType::class, 'second' => ContainerDataType::class],
            'entry_types_options' => ['first' => ['type' => FirstType::class], 'second' => ['type' => SecondType::class]],
            'allow_add' => true,
        ]);

        $form->submit([
            ['_key' => 'first', 'data' => ['name' => 'hello']],
            ['_key' => 'second', 'data' => ['label' => 'world']],
        ]);

        $this->assertEquals([
            ['data' => ['name' => 'hello']],
            ['data' => ['label' => 'world']],
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
            'allow_delete' => true,
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
            'allow_add' => false,
        ]);

        $view = $form->createView();

        $this->assertFalse($view->vars['allow_add']);
        $this->assertTrue($view->vars['allow_delete']);
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
            'allow_add' => true,
        ]);

        $form->submit([
            0 => ['name' => 'Bob'],
        ]);
    }

    public function testSubmitWithWrongType()
    {
        $this->expectException(UnexpectedTypeException::class);
        $form = $this->factory->create(PolyCollectionType::class, null, [
            'entry_types' => ['first' => FirstType::class],
        ]);

        $form->submit('something is wrong');
    }

    public function testSubmitWithEmptyData()
    {
        $form = $this->factory->create(PolyCollectionType::class, null, [
            'entry_types' => ['first' => FirstType::class],
        ]);

        $form->submit(null);

        $this->assertEquals([], $form->getData());
    }

    public function testSetData()
    {
        $this->expectException(UnexpectedTypeException::class);
        $form = $this->factory->create(PolyCollectionType::class, null, [
            'entry_types' => ['first' => FirstType::class],
        ]);
        $form->setData('something is wrong');
    }

    public function testMissingEntryTypes()
    {
        $this->expectException(MissingOptionsException::class);
        $this->factory->create(PolyCollectionType::class, null, []);
    }

    public function testNestedTypes()
    {
        $form = $this->factory->create(PolyCollectionType::class, null, [
            'entry_types' => [
                'nested' => NestedType::class,
                'first' => FirstType::class,
                'second' => SecondType::class,
            ],
            'allow_add' => true,
            'allow_delete' => true,
            'prototype_storage' => 'test',
        ]);

        $form->createView();

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

    public function testFilter()
    {
        $form = $this->factory->create(PolyCollectionType::class, null, [
            'entry_types' => [
                'nested' => NestedType::class,
                'first' => FirstType::class,
                'second' => SecondType::class,
            ],
            'allow_add' => true,
            'allow_delete' => true,
            'entry_type_filter' => function ($keys) {
                return ['nested'];
            },
        ]);

        $view = $form->createView();
        $this->assertEquals(['nested'], $view->vars['poly_collection_config']['entryKeys']);
    }

    public function testChangingSubmitIndex()
    {
        $data = new ItemsContainer();

        $first = new First('Bob');
        $second = new Second('Alice');

        $data->addItem($first);
        $data->addItem($second);

        $formOptions = [
            'entry_types' => [
                'first' => FirstType::class,
                'second' => SecondType::class,
            ],
            'entry_types_options' => [
                'first' => ['data_class' => First::class, 'uuid_field' => true],
                'second' => ['data_class' => Second::class, 'uuid_field' => true],
            ],
            'uuid_property' => 'uuid',
        ];

        $form = $this->factory->createNamedBuilder('container', options: [
            'data_class' => ItemsContainer::class,
        ])
            ->setData($data)
            ->add('items', PolyCollectionType::class, $formOptions)
            ->getForm();

        $form->submit([
            'items' => [
                0 => ['_key' => 'first', 'name' => 'Bob', 'uuid' => $first->uuid],
                2 => ['_key' => 'second', 'label' => 'Alice', 'uuid' => $second->uuid],
            ],
        ]);

        $this->assertTrue($form->isSubmitted(), 'submitted');
        $this->assertTrue($form->isValid(), 'valid');
        $this->assertTrue($second === $data->getItems()->get(1), 'same reference');
    }

    public function testSendingWrongAndMissingIndex()
    {
        $data = new ItemsContainer();
        $first = new First('Bob');
        $second = new Second('Alice');
        $data->addItem($first);
        $data->addItem($second);

        $formOptions = [
            'entry_types' => [
                'first' => FirstType::class,
                'second' => SecondType::class,
            ],
            'entry_types_options' => [
                'first' => ['data_class' => First::class, 'uuid_field' => true],
                'second' => ['data_class' => Second::class, 'uuid_field' => true],
            ],
            'uuid_property' => 'uuid',
        ];

        $form = $this->factory->createNamedBuilder('container', options: [
            'data_class' => ItemsContainer::class,
        ])
            ->setData($data)
            ->add('items', PolyCollectionType::class, $formOptions)
            ->getForm();

        $form->submit([
            'items' => [
                3 => ['_key' => 'first', 'name' => 'Bob2', 'uuid' => $first->uuid],
                6 => ['_key' => 'first', 'name' => 'Bob3', 'uuid' => '022208e6-3f95-4add-a24e-9f2ee04ddd83'],
            ],
        ]);

        $this->assertTrue($form->isSubmitted(), 'submitted');
        $this->assertTrue($form->isValid(), 'valid');
        $this->assertCount(2, $data->getItems());
        $this->assertTrue($first === $data->getItems()->get(0), 'same reference');
        $this->assertEquals('022208e6-3f95-4add-a24e-9f2ee04ddd83', $data->getItems()->get(2)->uuid);
    }
}

class UuidType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['uuid_field']) {
            $builder->add('uuid', TextType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'uuid_field' => false,
        ]);
    }
}

class FirstType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);
    }

    public function getParent()
    {
        return UuidType::class;
    }
}

class SecondType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label', TextType::class);
    }

    public function getParent()
    {
        return UuidType::class;
    }
}

class NestedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('children', PolyCollectionType::class, [
            'entry_types' => ['nested' => NestedType::class, 'first' => FirstType::class, 'second' => SecondType::class],
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => false,
            'prototype_storage' => 'test',
        ]);
    }
}

class ContainerDataType extends AbstractType
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

class ItemsContainer
{
    public Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function addItem(Item $item): void
    {
        $this->items->add($item);
    }

    public function removeItem(Item $item): void
    {
        $this->items->removeElement($item);
    }

    public function getItems(): Collection
    {
        return $this->items;
    }
}

interface Item
{
}

class First implements Item
{
    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public ?string $uuid = null,
    ) {
        if (null === $this->uuid) {
            $this->uuid = generateUuid();
        }
    }
}

class Second implements Item
{
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $uuid = null,
    ) {
        if (null === $this->uuid) {
            $this->uuid = generateUuid();
        }
    }
}

function generateUuid(): ?string
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xFFFF), mt_rand(0, 0xFFFF),
        mt_rand(0, 0xFFFF),
        mt_rand(0, 0x0FFF) | 0x4000,
        mt_rand(0, 0x3FFF) | 0x8000,
        mt_rand(0, 0xFFFF), mt_rand(0, 0xFFFF), mt_rand(0, 0xFFFF)
    );
}
