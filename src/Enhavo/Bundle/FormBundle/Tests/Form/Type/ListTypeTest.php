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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $form = $this->factory->create(ListType::class);

        $form->setData([
            0 => 'A',
            1 => 'B',
        ]);

        $form->submit([
            '0' => 'A',
            '1' => 'B',
        ]);

        $form->createView();

        $this->assertTrue($form->isSynchronized());
        $this->assertSame([
            0 => 'A',
            1 => 'B',
        ], $form->getData());
    }

    public function testOrderOfArray()
    {
        $form = $this->factory->create(ListType::class, null);

        $form->setData([
            0 => 'A',
            1 => 'B',
        ]);

        $form->submit([
            '1' => 'B',
            '0' => 'A',
        ]);

        $form->createView();

        $this->assertTrue($form->isSynchronized());
        $this->assertSame([
            0 => 'B',
            1 => 'A',
        ], $form->getData());
    }

    public function testCollectionAdd()
    {
        $data = new ArrayCollection();
        $data->add(new Node('zero'));
        $data->add(new Node('one'));

        $form = $this->factory->create(ListType::class, $data, [
            'entry_type' => NodeType::class,
            'by_reference' => true, // no parent class, so we call it by reference
        ]);

        $form->submit([
            '0' => ['name' => 'zero_0'],
            '1' => ['name' => 'one_1'],
            '2' => ['name' => 'two_2'],
        ]);

        $this->assertTrue($form->isSubmitted(), 'is submitted');
        $this->assertTrue($form->isValid(), 'is valid');
        $this->assertTrue($form->getData() === $data, 'same reference');
        $this->assertEquals(3, $form->getData()->count(), 'contains');
    }

    public function testCollectionAddWithNoReference()
    {
        $data = new Container();
        $data->addNode(new Node('zero'));
        $data->addNode(new Node('one'));

        $form = $this->factory->createNamedBuilder('container', options: [
            'data_class' => Container::class,
        ])
            ->setData($data)
            ->add('nodes', ListType::class, [
                'entry_type' => NodeType::class,
            ])
            ->getForm();

        $form->submit([
            'nodes' => [
                '0' => ['name' => 'zero_0'],
                '1' => ['name' => 'one_1'],
                '2' => ['name' => 'two_2'],
            ],
        ]);

        $this->assertTrue($form->isSubmitted(), 'is submitted');
        $this->assertTrue($form->isValid(), 'is valid');
        $this->assertTrue($form->getData() === $data, 'same reference');
        $this->assertEquals(3, $data->getNodes()->count(), 'contains');
    }

    public function testCollectionAddAndRecreateForm()
    {
        $data = new Container();
        $data->addNode(new Node('zero'));
        $data->addNode(new Node('one'));

        $form = $this->factory->createNamedBuilder('container', options: [
            'data_class' => Container::class,
        ])
            ->setData($data)
            ->add('nodes', ListType::class, [
                'entry_type' => NodeType::class,
                'uuid_property' => 'uuid',
            ])
            ->getForm();

        $formView = $form->createView();

        $nodeTwoUuid = generateUuid();

        $form->submit([
            'nodes' => [
                '0' => ['name' => 'zero_0', 'uuid' => $formView['nodes'][0]['uuid']->vars['value']],
                '1' => ['name' => 'one_1', 'uuid' => $formView['nodes'][1]['uuid']->vars['value']],
                '3' => ['name' => 'two_2', 'uuid' => $nodeTwoUuid],
            ],
        ]);

        $this->assertTrue($form->isSubmitted(), 'is submitted');
        $this->assertTrue($form->isValid(), 'is valid');

        $thirdElement = $form->getData()->getNodes()->get(2);
        $this->assertNotNull($thirdElement);
        $this->assertEquals($nodeTwoUuid, $thirdElement->uuid);

        // recreate form again
        $form = $this->factory->createNamedBuilder('container', options: [
            'data_class' => Container::class,
        ])
            ->setData($data)
            ->add('nodes', ListType::class, [
                'entry_type' => NodeType::class,
                'uuid_property' => 'uuid',
            ])
            ->getForm();

        $formView = $form->createView();

        $form->submit([
            'nodes' => [
                '0' => ['name' => 'zero_0', 'uuid' => $formView['nodes'][0]['uuid']->vars['value']],
                '1' => ['name' => 'one_1', 'uuid' => $formView['nodes'][1]['uuid']->vars['value']],
                '3' => ['name' => 'two_2', 'uuid' => $nodeTwoUuid],
            ],
        ]);

        $this->assertTrue($form->isSubmitted(), 'is submitted');
        $this->assertTrue($form->isValid(), 'is valid');
        $this->assertTrue($form->getData()->getNodes()->get(2) === $thirdElement, 'same reference');
    }
}

class Container
{
    public Collection $nodes;

    public function __construct()
    {
        $this->nodes = new ArrayCollection();
    }

    public function addNode(Node $children): void
    {
        $this->nodes->add($children);
    }

    public function removeNode(Node $children): void
    {
        $this->nodes->removeElement($children);
    }

    public function getNodes(): Collection
    {
        return $this->nodes;
    }
}

class Node
{
    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public ?string $uuid = null,
    ) {
        if (null === $this->uuid) {
            $this->uuid = generateUuid();
        }
    }
}

class NodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('uuid', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Node::class,
        ]);
    }
}

function generateUuid(): ?string
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xFFFF), mt_rand(0, 0xFFFF),
        mt_rand(0, 0xFFFF),
        mt_rand(0, 0x0FFF) | 0x4000,
        mt_rand(0, 0x3FFF) | 0x8000,
        mt_rand(0, 0xFFFF), mt_rand(0, 0xFFFF), mt_rand(0, 0xFFFF)
    );
}
