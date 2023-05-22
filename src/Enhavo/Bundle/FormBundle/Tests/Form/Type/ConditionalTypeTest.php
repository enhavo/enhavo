<?php

namespace Enhavo\Bundle\FormBundle\Tests\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\ConditionalType;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class ConditionalTypeTest extends TypeTestCase
{
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
            },
        ]);

        $form->getViewData();

        $this->assertEquals('Text', $form->getData());
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
