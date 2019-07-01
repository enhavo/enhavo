<?php

namespace Enhavo\Bundle\NewsletterBundle\Block;

use Enhavo\Bundle\NewsletterBundle\Entity\SubscribeBlock;
use Enhavo\Bundle\NewsletterBundle\Factory\SubscribeBlockFactory;
use Enhavo\Bundle\NewsletterBundle\Form\Type\SubscribeBlockType as SubscribeBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscribeBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => SubscribeBlock::class,
            'parent' => SubscribeBlock::class,
            'form' => SubscribeBlockFormType::class,
            'factory' => SubscribeBlockFactory::class,
            'repository' => 'EnhavoNewsletterBundle:SubscribeBlock',
            'template' => 'EnhavoNewsletterBundle:Theme/Block:subscribe.html.twig',
            'label' => 'Newsletter Subscribe',
            'translationDomain' => 'EnhavoNewsletterBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'newsletter_subscribe';
    }
}
