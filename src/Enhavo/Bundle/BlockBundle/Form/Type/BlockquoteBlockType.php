<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Model\Block\BlockquoteBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockquoteBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextareaType::class, [
            'label' => 'blockquoteText.form.label.blockquote',
            'translation_domain' => 'EnhavoBlockBundle',
        ]);

        $builder->add('author', TextType::class, [
            'label' => 'blockquoteText.form.label.author',
            'translation_domain' => 'EnhavoBlockBundle',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BlockquoteBlock::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_block_blockquote';
    }
}
