<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContactBundle\Block;

use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Enhavo\Bundle\ContactBundle\Entity\ContactBlock;
use Enhavo\Bundle\ContactBundle\Factory\ContactBlockFactory;
use Enhavo\Bundle\ContactBundle\Form\Type\ContactBlockType as ContactBlockFormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'model' => ContactBlock::class,
            'form' => ContactBlockFormType::class,
            'factory' => ContactBlockFactory::class,
            'template' => 'EnhavoContactBundle:Theme/Block:contact.html.twig',
            'label' => 'Contact',
            'translation_domain' => 'EnhavoContactBundle',
            'groups' => ['default', 'content'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'contact';
    }
}
