<?php

namespace Enhavo\Bundle\TaxonomyBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\TaxonomyBundle\Model\TermInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermType extends AbstractType
{
    public function __construct(
        private readonly string $dataClass
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'label' => 'form.label.name',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        $builder->add('text', WysiwygType::class, array(
            'label' => 'form.label.text',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        if ($options['parent']) {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                /** @var TermInterface $data */
                $form->add('parent', EntityType::class, [
                    'label' => 'form.label.parent',
                    'translation_domain' => 'EnhavoAppBundle',
                    'class' => $this->dataClass,
                    'placeholder' => '---',
                    'query_builder' => function (EntityRepository $er) use ($data) {
                        $query = $er->createQueryBuilder('p');
                        if ($data->getId()) {
                            $query->where('p.id != :id');
                            $query->andWhere('p.taxonomy = :taxonomy');
                            $query->setParameter('id', $data->getId());
                            $query->setParameter('taxonomy', $data->getTaxonomy());
                        }
                        return $query;
                    }
                ]);
            });
        }

        if ($options['slug']) {
            $builder->add('slug', TextType::class, [
                'label' => 'form.label.slug',
                'translation_domain' => 'EnhavoAppBundle'
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults( array(
            'data_class' => $this->dataClass,
            'parent' => false,
            'slug' => true,
        ));
    }
}
