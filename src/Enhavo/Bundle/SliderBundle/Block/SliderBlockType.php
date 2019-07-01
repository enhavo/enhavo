<?php

namespace Enhavo\Bundle\SliderBundle\Block;

use Enhavo\Bundle\SliderBundle\Entity\SliderBlock;
use Enhavo\Bundle\SliderBundle\Factory\SliderBlockFactory;
use Enhavo\Bundle\SliderBundle\Form\Type\SliderBlockType as SliderBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SliderBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => SliderBlock::class,
            'parent' => SliderBlock::class,
            'form' => SliderBlockFormType::class,
            'factory' => SliderBlockFactory::class,
            'repository' => 'EnhavoSliderBundle:SliderBlock',
            'template' => 'EnhavoSliderBundle:Theme/Block:slider.html.twig',
            'label' => 'Slider',
            'translationDomain' => 'EnhavoSliderBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'slider';
    }
}
