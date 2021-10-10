<?php

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Enhavo\Bundle\UserBundle\Form\Data\DeleteConfirm;
use Enhavo\Bundle\UserBundle\Validator\Constraints\UserEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\IsTrue;

class DeleteConfirmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', TextType::class, [
            'translation_domain' => 'EnhavoUserBundle',
            'label' => 'form.email',
            'constraints' => [
                new UserEmail()
            ]
        ]);

        $builder->add('password', PasswordType::class, [
            'translation_domain' => 'EnhavoUserBundle',
            'label' => 'form.password',
            'constraints' => [
                new UserPassword()
            ]
        ]);

        $builder->add('confirm', CheckboxType::class, [
            'translation_domain' => 'EnhavoUserBundle',
            'label' => 'delete_confirm',
            'constraints' => [
                new IsTrue()
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_token_id' => 'delete_confirm',
            'data_class' => DeleteConfirm::class
        ]);
    }
}
