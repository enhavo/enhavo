<?php

namespace esperanto\ProjectBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use esperanto\ReferenceBundle\Form\Type\ReferenceType as BaseReferenceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ReferenceType extends BaseReferenceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('detail_title', 'text', array(
            'label' => 'form.label.title.h1',
        ));

        $builder->add('landing_page',  'choice', array(
            'label' => 'form.label.landing_page',
            'choices'   => array(
                '1' => 'label.yes',
                '0' => 'label.no'
            ),
            'expanded' => true,
            'multiple' => false
        ));

        $builder->add('services', 'esperanto_category', array(
            'label' => 'form.label.services',
            'category_name' => 'reference-service',
            'attr' => array('class' => 'category-list')
        ));

        $builder->add('style', 'text', array(
            'label' => 'form.label.style',
        ));

        $builder->add('channel', 'text', array(
            'label' => 'form.label.channel',
        ));

        $builder->add('target_group', 'text', array(
            'label' => 'form.label.target_group',
        ));

        $builder->add('client', 'text', array(
            'label' => 'form.label.client',
        ));

        $builder->add('description', 'wysiwyg', array(
            'label' => 'form.label.description'
        ));

        $builder->add('preview_picture', 'esperanto_files', array(
            'label' => 'form.label.preview_picture',
            'information' => array('Bilder in der Größe 960x360 Pixel (oder ein vielfaches davon) hochladen')
        ));

        $builder->add('images', 'esperanto_files', array(
            'label' => 'form.label.pictures',
            'information' => array('Bilder in der Größe 320x180 Pixel (oder ein vielfaches davon) hochladen')
        ));

        $router = $this->router;
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($router) {
            $reference = $event->getData();
            $form = $event->getForm();

            if (!empty($reference) && $reference->getId()) {
                $url = $router->generate('esperanto_project_index', array(
                    'id' => $reference->getId(),
                    'slug' => $reference->getSlug(),
                ), true);

                $form->add('url', 'text', array(
                    'mapped' => false,
                    'data' => $url,
                    'disabled' => true,
                    'label' => 'form.label.page_link'
                ));
            }
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\ProjectBundle\Entity\Reference'
        ));
    }
}