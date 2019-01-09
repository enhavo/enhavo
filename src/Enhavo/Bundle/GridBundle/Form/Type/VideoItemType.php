<?php

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\GridBundle\Model\Item\VideoItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoItemType extends AbstractType
{
    private $translation;

    public function __construct($translation)
    {
        $this->translation = $translation;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle',
            'translation' => $this->translation
        ));

        $builder->add('url', TextType::class, array(
            'label' => 'video.form.label.url',
            'translation_domain' => 'EnhavoGridBundle'
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => VideoItem::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_grid_video_item';
    }
}