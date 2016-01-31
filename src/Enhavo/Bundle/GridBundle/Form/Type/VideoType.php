<?php

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\GridBundle\Entity\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Enhavo\Bundle\GridBundle\Item\Type\Video;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Enhavo\Bundle\GridBundle\Item\ItemFormType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class VideoType extends ItemFormType
{
    protected $configuration;

    public function __construct($configuration = null)
    {
        $this->configuration = $configuration;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text',array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('url', 'text',array(
            'label' => 'video.form.label.url',
            'translation_domain' => 'EnhavoGridBundle'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\GridBundle\Entity\Video'
        ));
    }

    public function getName()
    {
        return 'enhavo_grid_item_video';
    }
}