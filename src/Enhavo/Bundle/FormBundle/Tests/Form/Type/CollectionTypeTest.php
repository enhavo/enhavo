<?php

namespace Enhavo\Bundle\FormBundle\Tests\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\FormBundle\Form\Type\PolyCollectionType;
use Enhavo\Bundle\FormBundle\Tests\Type\FirstType;
use Enhavo\Bundle\FormBundle\Tests\Type\SecondType;
use Symfony\Component\Form\Test\TypeTestCase;

class CollectionTypeTest extends TypeTestCase
{
    public function testSubmit()
    {
        $form = $this->factory->create(PolyCollectionType::class, [], [
            'entry_types' => ['first' => FirstType::class, 'second' => SecondType::class],
            'allow_add' => true
        ]);

        $form->submit([
            ['_key' => 'first', 'name' => 'hello'],
            ['_key' => 'second', 'label' => 'world'],
        ]);

        $this->assertEquals([
            ['name' => 'hello'],
            ['label' => 'world']
        ], $form->getData());
    }
}
