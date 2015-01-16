<?php

namespace esperanto\ProjectBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use esperanto\NewsBundle\Form\Type\NewsType as BaseNewsType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class NewsType extends BaseNewsType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $router = $this->router;

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($router) {
            $news = $event->getData();
            $form = $event->getForm();

            if (!empty($news) && $news->getId()) {
                $url = $router->generate('esperanto_project_index', array(
                    'id' => $news->getId(),
                    'slug' => $news->getSlug(),
                ), true);

                $form->add('link', 'text', array(
                    'mapped' => false,
                    'data' => $url,
                    'disabled' => true
                ));
            }
        });

        $builder->add('landing_page',  'choice', array(
            'label' => 'form.label.landing_page',
            'choices'   => array(
                '1' => 'label.yes',
                '0' => 'label.no'
            ),
            'expanded' => true,
            'multiple' => false
        ));

        $builder->add('sticky', 'choice', array(
            'label' => 'form.label.sticky',
            'choices'   => array(
                '1' => 'label.yes',
                '0' => 'label.no'
            ),
            'expanded' => true,
            'multiple' => false
        ));


        $builder->add('picture', 'esperanto_files', array(
            'information' => array('Bilder in der Größe 960x360 Pixel (oder ein vielfaches davon) hochladen'),
            'label' => 'form.label.picture'
        ));

        $builder->add('content', 'esperanto_content', array(

        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => 'esperanto\ProjectBundle\Entity\News'
        ));
    }
}