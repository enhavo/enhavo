<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Enhavo\Bundle\BlockBundle\Factory\SliderBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\SliderBlockType as SliderBlockFormType;
use Enhavo\Bundle\BlockBundle\Model\Block\SliderBlock;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SliderBlockType extends AbstractBlockType
{
    /** @var EntityRepository */
    private $repository;

    /**
     * SliderBlockType constructor.
     */
    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createViewData(BlockInterface $block, Data $data, $resource, array $options)
    {
        $data['slides'] = $this->repository->findAll();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => SliderBlock::class,
            'form' => SliderBlockFormType::class,
            'factory' => SliderBlockFactory::class,
            'template' => 'theme/block/slider.html.twig',
            'label' => 'Slider',
            'translation_domain' => 'EnhavoSliderBundle',
            'groups' => ['default', 'content'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'slider';
    }
}
