<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\PageBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;
use Enhavo\Bundle\ContentBundle\Form\Type\ContentType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    public function __construct(
        private readonly string $dataClass,
        private readonly array $specials,
        private readonly array $types,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', BlockNodeType::class, [
            'label' => 'form.label.content',
            'translation_domain' => 'EnhavoAppBundle',
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $form = $event->getForm();
            $data = $event->getData();

            $form->add('parent', EntityType::class, [
                'label' => 'page.label.parent',
                'translation_domain' => 'EnhavoPageBundle',
                'class' => $this->dataClass,
                'placeholder' => '---',
                'query_builder' => function (EntityRepository $er) use ($data) {
                    $query = $er->createQueryBuilder('p');
                    if ($data && $data->getId()) {
                        $query->where('p.id != :id');
                        $query->setParameter('id', $data->getId());
                    }

                    return $query;
                },
            ]);

            if (count($this->specials)) {
                $form->add('special', SpecialsType::class);
            }

            if (count($this->types)) {
                $form->add('type', TypesType::class);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'routable' => true,
        ]);
    }

    public function getParent(): ?string
    {
        return ContentType::class;
    }
}
