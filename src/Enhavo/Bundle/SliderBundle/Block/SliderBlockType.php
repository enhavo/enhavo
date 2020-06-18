<?php

namespace Enhavo\Bundle\SliderBundle\Block;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\SliderBundle\Entity\SliderBlock;
use Enhavo\Bundle\SliderBundle\Factory\SliderBlockFactory;
use Enhavo\Bundle\SliderBundle\Form\Type\SliderBlockType as SliderBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SliderBlockType extends AbstractBlockType
{
    /** @var EntityRepository */
    private $repository;

    public function createViewData(BlockInterface $block, $resource, array $options)
    {
        $data = parent::createViewData($block, $resource, $options);
        $data['slides'] = $this->repository->findAll();
        return $data;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'model' => SliderBlock::class,
            'form' => SliderBlockFormType::class,
            'factory' => SliderBlockFactory::class,
            'template' => 'theme/block/slider.html.twig',
            'label' => 'Slider',
            'translation_domain' => 'EnhavoSliderBundle',
            'groups' => ['default', 'content']
        ]);
    }
    public static function getName(): ?string
    {
        return 'slider';
    }
}
