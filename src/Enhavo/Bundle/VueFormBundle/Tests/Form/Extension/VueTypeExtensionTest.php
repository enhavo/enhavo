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

use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\TypeTestCase;

class VueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        return [
            new VueTypeExtension(),
        ];
    }

    public function testViewVars()
    {
        $vueForm = new VueForm();

        $form = $this->factory->create(TextType::class, null, [
            'component' => 'myComponent',
            'component_model' => 'myModel',
            'component_visitors' => 'vistor',
            'row_component' => 'myRowComponent',
        ]);

        $data = $vueForm->createData($form->createView());

        $this->assertEquals('myComponent', $data['component']);
        $this->assertEquals('myModel', $data['componentModel']);
        $this->assertEquals('vistor', $data['componentVisitors'][0]);
        $this->assertEquals('myRowComponent', $data['rowComponent']);
    }
}
