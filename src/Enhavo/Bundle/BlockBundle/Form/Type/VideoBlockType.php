<?php

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Model\Block\VideoBlock;
use Enhavo\Bundle\FormBundle\Form\Type\HeadLineType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', HeadLineType::class, array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('url', TextType::class, array(
            'label' => 'video.form.label.url',
            'translation_domain' => 'EnhavoBlockBundle'
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => VideoBlock::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_block_video';
    }
}
