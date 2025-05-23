<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Tests\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class BooleanTypeTest extends TypeTestCase
{
    private $translator;

    protected function setUp(): void
    {
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->translator->method('trans')->willReturnCallback(function ($id) {
            return $id;
        });
        parent::setUp();
    }

    protected function getExtensions()
    {
        $type = new BooleanType($this->translator);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public function testSubmitValidDataToTrue()
    {
        $form = $this->factory->create(BooleanType::class, null, []);

        $form->setData(false);
        $form->submit('1');

        $this->assertTrue($form->isSynchronized());
        $data = $form->getData();
        $this->assertTrue(true === $data);
    }

    public function testSubmitValidDataToFalse()
    {
        $form = $this->factory->create(BooleanType::class, null, []);

        $form->setData(true);
        $form->submit('0');
        $form->createView();

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue(false === $form->getData());
    }

    public function testDefaultOptionValueWithTrue()
    {
        $form = $this->factory->create(BooleanType::class, null, ['default' => true]);
        $view = $form->createView();
        $this->assertEquals(BooleanType::VALUE_TRUE, $view->vars['value']);
    }

    public function testDefaultOptionValueWithFalse()
    {
        $form = $this->factory->create(BooleanType::class, null, ['default' => false]);
        $view = $form->createView();
        $this->assertEquals(BooleanType::VALUE_FALSE, $view->vars['value']);
    }

    public function testDefaultOptionValueWithNull()
    {
        $form = $this->factory->create(BooleanType::class, null, ['default' => null]);
        $view = $form->createView();
        $this->assertEquals(BooleanType::VALUE_NULL, $view->vars['value']);
    }
}
