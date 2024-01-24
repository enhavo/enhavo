<?php

namespace Enhavo\Bundle\FormBundle\Tests\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\ConditionalType;
use Enhavo\Bundle\VueFormBundle\Form\Extension\PasswordVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConditionalTypeTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        return [
            new VueTypeExtension()
        ];
    }
    protected function getExtensions()
    {
        return [
            new PreloadedExtension([new ConditionalType(new VueForm())], [])
        ];
    }

    public function testSubmitValidData()
    {
        $form = $this->factory->create(ConditionalType::class, null, [
            'entry_types' => [
                'a' => FormAType::class,
                'b' => FormBType::class,
            ],
            'entry_type_resolver' => 'a',
        ]);

        $form->submit([
            'conditional' => [
                'a' => 'Hello',
            ],
            'key' => 'a'
        ]);

        $data = $form->getData();

        $this->assertArrayHasKey('a', $data);
        $this->assertArrayNotHasKey('b', $data);
        $this->assertEquals('Hello', $data['a']);
    }

    public function testPreSetData()
    {
        $form = $this->factory->create(ConditionalType::class, 'Text', [
            'entry_types' => [
                'text' => TextType::class,
                'date' => DateType::class,
            ],
            'entry_type_resolver' => function($data) {
                if (is_string($data)) {
                    return 'text';
                } elseif ($data instanceof \DateTime) {
                    return 'date';
                }
                return null;
            },
        ]);

        $form->getViewData();

        $this->assertEquals('Text', $form->getData());
    }

    public function testSwitchType()
    {
        $car = new Car();
        $car->setMotor('bmw');

        $form = $this->factory->create(ConditionalType::class, $car, [
            'entry_types' => [
                'car' => CarType::class,
                'bike' => BikeType::class,
            ],
            'entry_type_resolver' => function($data) {
                if ($data instanceof Car) {
                    return 'car';
                } elseif ($data instanceof Bike) {
                    return 'bike';
                }
                return null;
            },
        ]);

        $form->submit([
            'conditional' => [
                'pedals' => 'MyPedals',
            ],
            'key' => 'bike'
        ]);

        $this->assertInstanceOf(Bike::class, $form->getData());
    }

    public function testKeyValue()
    {
        $car = new Car();

        $form = $this->factory->create(ConditionalType::class, $car, [
            'entry_types' => [
                'car' => CarType::class,
                'bike' => BikeType::class,
            ],
            'entry_type_resolver' => function($data) {
                if ($data instanceof Car) {
                    return 'car';
                } elseif ($data instanceof Bike) {
                    return 'bike';
                }
                return null;
            },
        ]);

        $view = $form->createView();

        $this->assertEquals('car', $view->children['key']->vars['value']);
    }

    public function testNested()
    {
        $car = new Car();

        $form = $this->factory->create(ConditionalType::class, $car, [
            'entry_types' => [
                'car' => ConditionalType::class,
                'bike' => BikeType::class,
            ],
            'entry_type_resolver' => function($data) {
                if ($data instanceof Car) {
                    return 'car';
                } elseif ($data instanceof Bike) {
                    return 'bike';
                }
                return null;
            },
            'entry_types_options' => [
                'car' => [
                    'entry_types' => [
                        'car' => CarType::class,
                        'bike' => BikeType::class,
                    ],
                    'entry_type_resolver' => function($data) {
                        if ($data instanceof Car) {
                            return 'car';
                        } elseif ($data instanceof Bike) {
                            return 'bike';
                        }
                        return null;
                    },
                ]
            ]
        ]);

        $view = $form->createView();

        $this->assertEquals('car', $view->children['key']->vars['value']);
    }

    public function testSubmitWithEmptyKeyAndTypes()
    {
        $car = new Car();

        $form = $this->factory->create(ConditionalType::class, $car, [
            'entry_types' => [],
            'entry_type_resolver' => function($data) {
                return null;
            },
            'entry_types_options' => []
        ]);

        $form->submit([
            'conditional' => [],
            'key' => ''
        ]);

        $this->assertNull($form->getData());
    }
}

class FormAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('a', TextType::class);
    }
}

class FormBType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('b', TextType::class);
    }
}

class Car
{
    private ?string $motor = null;

    public function getMotor(): ?string
    {
        return $this->motor;
    }

    public function setMotor(?string $motor): void
    {
        $this->motor = $motor;
    }
}

class Bike
{
    private ?string $pedals = null;

    public function getPedals(): ?string
    {
        return $this->pedals;
    }

    public function setPedals(?string $pedals): void
    {
        $this->pedals = $pedals;
    }
}

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('motor', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Car::class);
    }
}

class BikeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pedals', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Bike::class);
    }
}
