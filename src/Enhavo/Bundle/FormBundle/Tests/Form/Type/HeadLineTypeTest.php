<?php

namespace Enhavo\Bundle\FormBundle\Tests\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\HeadLineType;
use Symfony\Component\Form\Test\TypeTestCase;

class HeadLineTypeTest extends TypeTestCase
{
    public function testDefaultChoice()
    {
        $form = $this->factory->create(HeadLineType::class);

        $form->submit([
            'text' => 'Hello World',
            'tag' => ''
        ]);

        $this->assertEquals('Hello World', $form->getData());
    }

    public function testWithChoice()
    {
        $form = $this->factory->create(HeadLineType::class);

        $form->submit([
            'text' => 'Hello World',
            'tag' => 'h1'
        ]);

        $this->assertEquals('<h1>Hello World</h1>', $form->getData());
    }
}
