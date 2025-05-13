<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'group.label.name',
            'translation_domain' => 'EnhavoNewsletterBundle',
        ]);

        $builder->add('code', TextType::class, [
            'label' => 'group.label.code',
            'translation_domain' => 'EnhavoNewsletterBundle',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Enhavo\Bundle\NewsletterBundle\Entity\Group',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_newsletter_group';
    }
}
