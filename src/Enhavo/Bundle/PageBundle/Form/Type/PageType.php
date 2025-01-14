<?php

namespace Enhavo\Bundle\PageBundle\Form\Type;

use Enhavo\Bundle\ContentBundle\Form\Type\ContentType;
use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class PageType extends AbstractType
{
    public function __construct(
        private readonly string $dataClass,
        private readonly array $specialPages,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', BlockNodeType::class, array(
            'label' => 'form.label.content',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            $form->add('parent', EntityType::class, array(
                'label' => 'page.label.parent',
                'translation_domain' => 'EnhavoPageBundle',
                'class' => $this->dataClass,
                'placeholder' => '---',
                'query_builder' => function (EntityRepository $er) use ($data) {
                    $query =  $er->createQueryBuilder('p');
                    if ($data && $data->getId()) {
                        $query->where('p.id != :id');
                        $query->setParameter('id', $data->getId());
                    }
                    return $query;
                }
            ));

            if (count($this->specialPages)) {
                $form->add('code', SpecialPageType::class, array(
                    'label' => 'page.label.special_page',
                    'translation_domain' => 'EnhavoPageBundle',
                ));
            }
        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            $form->add('parent', EntityType::class, array(
                'label' => 'page.label.parent',
                'translation_domain' => 'EnhavoPageBundle',
                'class' => $this->dataClass,
                'placeholder' => '---',
                'query_builder' => function (EntityRepository $er) use ($data) {
                    $query =  $er->createQueryBuilder('p');
                    if ($data && $data->getId()) {
                        $query->where('p.id != :id');
                        $query->setParameter('id', $data->getId());
                    }
                    return $query;
                }
            ));
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults( array(
            'data_class' => $this->dataClass,
            'routable' => true
        ));
    }

    public function getParent(): ?string
    {
        return ContentType::class;
    }
}
