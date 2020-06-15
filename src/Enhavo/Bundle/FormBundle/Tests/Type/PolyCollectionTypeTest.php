<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 06:57
 */

namespace Enhavo\Bundle\FormBundle\Tests\Type;

use Enhavo\Bundle\FormBundle\Form\Type\PolyCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PolyCollectionTypeTest extends TypeTestCase
{
    public function testSubmit()
    {
        $form = $this->factory->create(PolyCollectionType::class, [], [
            'entry_types' => [
                'first' => FirstType::class,
                'second' => SecondType::class
            ],
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
            'entry_types' => [
                'first' => FirstType::class,
                'second' => SecondType::class
            ],
            'entry_type_resolver' => function($data) {
                return $data['key'];
            },
            'allow_add' => true
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
            'entry_types' => [
                'first' => ContainerType::class,
                'second' => ContainerType::class,
            ],
            'entry_types_options' => [
                'first' => [
                    'type' => FirstType::class
                ],
                'second' => [
                    'type' => SecondType::class
                ]
            ],
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
