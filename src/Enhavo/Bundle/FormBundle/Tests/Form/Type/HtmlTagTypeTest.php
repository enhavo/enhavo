<?php

namespace Enhavo\Bundle\FormBundle\Tests\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\HtmlTagType;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Test\TypeTestCase;

class HtmlTagTypeTest extends TypeTestCase
{
    public function testTagChoice()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'tag_choices' => ['p', 'span']
        ]);

        $form->setData(false);
        $form->submit([
            'text' => 'Hello World',
            'tag' => 'p'
        ]);

        $this->assertEquals('<p>Hello World</p>', $form->getData());
    }

    public function testTagChoiceWithDefault()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'tag_choices' => ['p', 'span']
        ]);

        $form->setData(false);
        $form->submit([
            'text' => 'Hello World'
        ]);

        $this->assertEquals('Hello World', $form->getData());
    }

    public function testTagChoiceWithChoice()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'tag_choices' => ['p', 'span']
        ]);

        $form->setData(false);
        $form->submit([
            'text' => 'Hello World',
            'tag' => 'span'
        ]);

        $this->assertEquals('<span>Hello World</span>', $form->getData());
    }


    public function testTagOption()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'tag' => 'p'
        ]);

        $form->setData(false);
        $form->submit([
            'text' => 'Hello World',
        ]);

        $this->assertEquals('<p>Hello World</p>', $form->getData());
    }

    public function testClassChoiceWithDefault()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'class_choices' => ['foo', 'bar'],
            'tag' => 'p'
        ]);

        $form->setData(false);
        $form->submit([
            'text' => 'Hello World'
        ]);

        $this->assertEquals('<p>Hello World</p>', $form->getData());
    }

    public function testClassChoiceWithChoice()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'class_choices' => ['foo', 'bar'],
            'tag' => 'p'
        ]);

        $form->setData(false);
        $form->submit([
            'text' => 'Hello World',
            'class' => 'foo'
        ]);

        $this->assertEquals('<p class="foo">Hello World</p>', $form->getData());
    }

    public function testClassOption()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'class' => 'foobar',
            'tag' => 'p'
        ]);

        $form->setData(false);
        $form->submit([
            'text' => 'Hello World',
        ]);

        $this->assertEquals('<p class="foobar">Hello World</p>', $form->getData());
    }

    public function testSetDataWithClassAndTag()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'tag' => 'h1',
            'class_choices' => ['foo', 'bar'],
            'tag_choices' => ['p', 'span']
        ]);

        $form->setData('<p class="foo">Hello World</p>');
        $view = $form->getViewData();

        $this->assertEquals('Hello World', $view['text']);
        $this->assertEquals('foo', $view['class']);
        $this->assertEquals('p', $view['tag']);
    }

    public function testSetDataWithClass()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'tag' => 'h1',
            'tag_choices' => ['p', 'span']
        ]);

        $form->setData('<p>Hello World</p>');
        $view = $form->getViewData();

        $this->assertEquals('Hello World', $view['text']);
        $this->assertEquals('p', $view['tag']);
    }

    public function testClassChoicesMissConfiguration()
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->factory->create(HtmlTagType::class, null, [
            'class_choices' => ['p', 'span']
        ]);
    }

    public function testClassMissConfiguration()
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->factory->create(HtmlTagType::class, null, [
            'class' => 'foobar'
        ]);
    }
}
