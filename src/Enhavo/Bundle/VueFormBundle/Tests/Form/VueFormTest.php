<?php

namespace Enhavo\Bundle\VueFormBundle\Tests\Form;

use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Enhavo\Bundle\VueFormBundle\Form\VueType\FormVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueType\TextVueType;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class VueFormTest extends TypeTestCase
{
    public function createInstance(VueFormDependencies $dependencies)
    {
        $instance = new VueForm();
        $instance->setContainer($dependencies->container);
        return $instance;
    }

    public function createDependencies()
    {
        $dependencies = new VueFormDependencies;
        $dependencies->container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        return $dependencies;
    }

    public function testCreateData()
    {
        $dependencies = $this->createDependencies();
        $vueForm = $this->createInstance($dependencies);

        $form = $this->factory->create(TestType::class);

        $data = $vueForm->createData($form->createView());

        $this->assertCount(2, $data['children']);
        $this->assertTrue($data['root']);
    }

    public function testSubmitData()
    {
        $dependencies = $this->createDependencies();
        $vueForm = $this->createInstance($dependencies);

        $data = [
            'name' => 'my_form_name',
            'compound' => true,
            'children' => [
                'child_form' => [
                    'name' => 'child_form',
                    'value' => 'test',
                    'compound' => false,
                ],
                'other_child_form' => [
                    'name' => 'other_child_form',
                    'value' => '',
                    'compound' => true,
                    'children' => [
                        'deep_child' => [
                            'name' => 'deep_child',
                            'value' => 'foobar',
                            'compound' => false,
                        ]
                    ]
                ]
            ]
        ];

        $submittedData = $vueForm->submit($data);

        $this->assertIsArray($submittedData);
        $this->assertEquals('test', $submittedData['child_form']);
        $this->assertEquals('foobar', $submittedData['other_child_form']['deep_child']);
    }
}

class VueFormDependencies
{
    /** @var ContainerInterface|MockObject */
    public $container;
}

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('property_one', TextType::class);
        $builder->add('property_two', SubTestType::class);

    }
}

class SubTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('sub_property_one', TextType::class);
        $builder->add('sub_property_two', TextType::class);
    }
}
