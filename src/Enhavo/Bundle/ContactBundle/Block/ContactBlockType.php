<?php

namespace Enhavo\Bundle\ContactBundle\Block;

use Enhavo\Bundle\ContactBundle\Entity\ContactBlock;
use Enhavo\Bundle\ContactBundle\Factory\ContactBlockFactory;
use Enhavo\Bundle\ContactBundle\Form\Type\ContactBlockType as ContactBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => ContactBlock::class,
            'parent' => ContactBlock::class,
            'form' => ContactBlockFormType::class,
            'factory' => ContactBlockFactory::class,
            'repository' => 'EnhavoContactBundle:ContactBlock',
            'template' => 'EnhavoContactBundle:Theme/Block:contact.html.twig',
            'label' => 'Contact',
            'translationDomain' => 'EnhavoContactBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'contact';
    }
}
