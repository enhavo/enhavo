<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\VueFormBundle\Test\Form\Extension;

use Enhavo\Bundle\ResourceBundle\Tests\Mock\TranslatorMock;
use Enhavo\Bundle\VueFormBundle\Form\Extension\BaseVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\TypeTestCase;

class BaseVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        return [
            new VueTypeExtension(),
            new BaseVueTypeExtension(new TranslatorMock('_translated')),
        ];
    }

    public function testViewVars()
    {
        $vueForm = new VueForm();

        $form = $this->factory->create(TextType::class, null, [
            'label' => 'myLabel',
            'attr' => [
                'placeholder' => 'myPlaceholder',
                'title' => 'myTitle',
            ],
        ]);

        $data = $vueForm->createData($form->createView());

        $this->assertArrayHasKey('disabled', $data);
        $this->assertArrayHasKey('attr', $data);
        $this->assertArrayHasKey('rowAttr', $data);
        $this->assertArrayHasKey('labelFormat', $data);

        $this->assertEquals('myLabel_translated', $data['label']);
        $this->assertEquals('myPlaceholder_translated', $data['attr']['placeholder']);
        $this->assertEquals('myTitle_translated', $data['attr']['title']);
    }
}
