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

use Enhavo\Bundle\FormBundle\Form\Type\HtmlTagType;
use Symfony\Component\Form\Test\TypeTestCase;

class HtmlTagTypeTest extends TypeTestCase
{
    public function testTagChoice()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'tag_choices' => ['p', 'span'],
        ]);

        $form->setData(false);
        $form->submit([
            'text' => 'Hello World',
            'tag' => 'p',
        ]);

        $this->assertEquals('<p>Hello World</p>', $form->getData());
    }

    public function testTagChoiceWithDefault()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'tag_choices' => ['p', 'span'],
        ]);

        $form->setData(false);
        $form->submit([
            'text' => 'Hello World',
        ]);

        $this->assertEquals('Hello World', $form->getData());
    }

    public function testTagChoiceWithChoice()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'tag_choices' => ['p', 'span'],
        ]);

        $form->setData(false);
        $form->submit([
            'text' => 'Hello World',
            'tag' => 'span',
        ]);

        $this->assertEquals('<span>Hello World</span>', $form->getData());
    }

    public function testTagOption()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'tag_empty_data' => 'p',
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
            'tag_empty_data' => 'p',
        ]);

        $form->setData(false);
        $form->submit([
            'text' => 'Hello World',
        ]);

        $this->assertEquals('<p>Hello World</p>', $form->getData());
    }

    public function testClassChoiceWithChoice()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'class_choices' => ['foo', 'bar'],
            'tag_empty_data' => 'p',
        ]);

        $form->setData(false);
        $form->submit([
            'text' => 'Hello World',
            'class' => 'foo',
        ]);

        $this->assertEquals('<p class="foo">Hello World</p>', $form->getData());
    }

    public function testClassOption()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'class_empty_data' => 'foobar',
            'tag_empty_data' => 'p',
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
            'tag_empty_data' => 'h1',
            'tag_choices' => ['p', 'span'],
            'class_choices' => ['foo', 'bar'],
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
            'tag_empty_data' => 'h1',
            'tag_choices' => ['p', 'span'],
        ]);

        $form->setData('<p>Hello World</p>');
        $view = $form->getViewData();

        $this->assertEquals('Hello World', $view['text']);
        $this->assertEquals('p', $view['tag']);
    }

    public function testTextWithHTML()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'tag_choices' => ['h1', 'h2'],
        ]);

        $form->submit([
            'text' => '<p>Hello World</p>',
            'tag' => 'h2',
        ]);

        $this->assertEquals('<h2><p>Hello World</p></h2>', $form->getData());
    }

    public function testReadTextWithTag()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'tag_choices' => ['h1', 'h2'],
        ]);

        $form->setData('<h2><p>Hello World</p></h2>');
        $view = $form->getViewData();

        $this->assertEquals('<p>Hello World</p>', $view['text']);
        $this->assertEquals('h2', $view['tag']);
    }

    public function testReadTextWithClass()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'tag_choices' => ['h1', 'h2'],
        ]);

        $form->setData('<h2><p class="foobar">Hello World</p></h2>');
        $view = $form->getViewData();

        $this->assertEquals('<p class="foobar">Hello World</p>', $view['text']);
        $this->assertEquals('h2', $view['tag']);
    }

    public function testFallbackClassTag()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'class_choices' => ['foo', 'bar'],
        ]);

        $form->submit([
            'text' => 'Hello World',
            'class' => 'foo',
        ]);

        $this->assertEquals('<span class="foo">Hello World</span>', $form->getData());
    }

    public function testNoClassAndTag()
    {
        $form = $this->factory->create(HtmlTagType::class, null, [
            'class_choices' => ['foo', 'bar'],
            'tag_choices' => ['h1', 'h2'],
        ]);

        $form->submit([
            'text' => 'Hello World',
            'tag' => '',
            'class' => '',
        ]);

        $this->assertEquals('Hello World', $form->getData());
    }
}
