<?php

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Enhavo\Bundle\UserBundle\Form\Data\ResetPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author blutze-media
 */
class ResetPasswordRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('userIdentifier', TextType::class, [
            'label' => $options['identifier_label'],
            'translation_domain' => $options['identifier_translation_domain'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ResetPassword::class,
            'identifier_label' => 'security.login.email',
            'identifier_translation_domain' => 'EnhavoUserBundle',
        ]);
    }
}
