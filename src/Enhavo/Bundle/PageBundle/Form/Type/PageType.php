<?php

namespace Enhavo\Bundle\PageBundle\Form\Type;

use Enhavo\Bundle\ContentBundle\Form\Type\ContentType;
use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class PageType extends AbstractType
{
    /** @var string */
    private $dataClass;

    /** @var array */
    private $specialPages;

    public function __construct($dataClass, $specialPages)
    {
        $this->dataClass = $dataClass;
        $this->specialPages = $specialPages;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', BlockNodeType::class, array(
            'label' => 'form.label.content',
            'translation_domain' => 'EnhavoAppBundle',
            'item_groups' => ['layout'],
        ));

        $specialPages = $this->specialPages;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($specialPages) {
            $form = $event->getForm();
            $data = $event->getData();

            $form->add('parent', EntityType::class, array(
                'label' => 'page.label.parent',
                'translation_domain' => 'EnhavoPageBundle',
                'class' => $this->dataClass,
                'placeholder' => '---',
                'query_builder' => function (EntityRepository $er) use ($data) {
                    $query =  $er->createQueryBuilder('p');
                    if($data instanceof ResourceInterface && $data->getId()) {
                        $query->where('p.id != :id');
                        $query->setParameter('id', $data->getId());
                    }
                    return $query;
                }
            ));

            if (count($specialPages)) {
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
                    if($data instanceof ResourceInterface && $data->getId()) {
                        $query->where('p.id != :id');
                        $query->setParameter('id', $data->getId());
                    }
                    return $query;
                }
            ));
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => $this->dataClass,
            'routable' => true
        ));
    }

    public function getParent()
    {
        return ContentType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_page_page';
    }
}
