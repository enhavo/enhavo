<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Test\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Extension\ArrangementVueTypeExtension;
use Enhavo\Bundle\ResourceBundle\Tab\ArrangementParser;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\TypeTestCase;

class ArrangementVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        return [
            new VueTypeExtension(),
            new ArrangementVueTypeExtension(new ArrangementParser()),
        ];
    }

    public function testArrangement()
    {
        $form = $this->factory->createBuilder(FormType::class, null, ['arrangement' => 'name | title{2}'])
            ->add('name', TextType::class)
            ->add('title', TextType::class)
            ->getForm();

        $data = (new VueForm())->createData($form->createView());

        $this->assertEquals([
            [['key' => 'name', 'size' => 1], ['key' => 'title', 'size' => 2]],
        ], $data['arrangement']);
    }

    public function testWithoutArrangement()
    {
        $form = $this->factory->create(FormType::class);

        $data = (new VueForm())->createData($form->createView());

        $this->assertArrayNotHasKey('arrangement', $data);
    }
}