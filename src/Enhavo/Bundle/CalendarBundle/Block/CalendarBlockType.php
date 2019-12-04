<?php

namespace Enhavo\Bundle\CalendarBundle\Block;

use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\CalendarBundle\Entity\CalendarBlock;
use Enhavo\Bundle\CalendarBundle\Factory\CalendarBlockFactory;
use Enhavo\Bundle\CalendarBundle\Form\Type\CalendarBlockType as CalendarBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarBlockType extends AbstractBlockType
{
    public function createViewData(BlockInterface $block, $resource, array $options)
    {
        $data = parent::createViewData($block, $resource, $options);
        $data['appointments'] = $this->container->get('enhavo_calendar.repository.appointment')->findAll();
        return $data;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => CalendarBlock::class,
            'parent' => CalendarBlock::class,
            'form' => CalendarBlockFormType::class,
            'factory' => CalendarBlockFactory::class,
            'repository' => 'EnhavoCalendarBundle:CalendarBlock',
            'template' => 'theme/block/calendar.html.twig',
            'label' => 'Calendar',
            'translationDomain' => 'EnhavoCalendarBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'calendar';
    }
}
