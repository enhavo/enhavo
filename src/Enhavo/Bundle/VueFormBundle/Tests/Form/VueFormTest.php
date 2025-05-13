<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\VueFormBundle\Tests\Form;

use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueDataHelperTrait;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Test\TypeTestCase;

class VueFormTest extends TypeTestCase
{
    use VueDataHelperTrait;

    protected function getTypeExtensions()
    {
        return [
            new VueTypeExtension(),
        ];
    }

    public function createInstance(VueFormDependencies $dependencies)
    {
        $instance = new VueForm();

        return $instance;
    }

    public function createDependencies()
    {
        $dependencies = new VueFormDependencies();

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

    public function testNormalizeData()
    {
        $dependencies = $this->createDependencies();
        $vueForm = $this->createInstance($dependencies);

        $form = $this->factory->create(TestType::class);
        $viewData = $form->createView();

        $this->getVueData($viewData)->addNormalizer(function (FormView $formView, VueData $vueData): void {
            $this->assertCount(2, $vueData->getChildren());
            $vueData['newKey'] = 'newValue';
        });

        $data = $vueForm->createData($viewData);

        $this->assertEquals('newValue', $data['newKey']);
    }
}

class VueFormDependencies
{
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
