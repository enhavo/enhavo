<?php

namespace Enhavo\Bundle\NewsletterBundle\Block;

use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Enhavo\Bundle\NewsletterBundle\Entity\SubscribeBlock;
use Enhavo\Bundle\NewsletterBundle\Factory\SubscribeBlockFactory;
use Enhavo\Bundle\NewsletterBundle\Form\Type\SubscribeBlockType as SubscribeBlockFormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscribeBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'model' => SubscribeBlock::class,
            'form' => SubscribeBlockFormType::class,
            'factory' => SubscribeBlockFactory::class,
            'template' => 'theme/block/subscribe.html.twig',
            'label' => 'Newsletter Subscribe',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public static function getName(): ?string
    {
        return 'newsletter_subscribe';
    }
}
