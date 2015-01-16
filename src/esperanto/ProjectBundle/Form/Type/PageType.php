<?php
namespace esperanto\ProjectBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use esperanto\PageBundle\Form\Type\PageType as BasePageType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;

class PageType extends BasePageType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $router = $this->router;
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($router) {
            $page = $event->getData();
            $form = $event->getForm();

            if (!empty($page) && $page->getId()) {
                $url = $router->generate('esperanto_project_index', array(
                    'id' => $page->getId(),
                    'slug' => $page->getSlug(),
                ), true);

                $form->add('link', 'text', array(
                    'mapped' => false,
                    'data' => $url,
                    'disabled' => true
                ));
            }
        });

        $builder->add('picture', 'esperanto_files', array(
            'information' => array('Bilder in der Größe 960x360 Pixel (oder ein vielfaches davon) hochladen'),
            'label' => 'form.label.picture'
        ));

        $builder->add('content', 'esperanto_content', array(

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
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\ProjectBundle\Entity\Page'
        ));
    }
}