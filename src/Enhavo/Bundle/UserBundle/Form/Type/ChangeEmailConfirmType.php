<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Enhavo\Bundle\UserBundle\Form\Data\ChangeEmail;
use Enhavo\Bundle\UserBundle\Validator\Constraints\EmailNotExists;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangeEmailConfirmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('currentPassword', PasswordType::class, [
            'label' => 'form.current_password',
            'translation_domain' => 'EnhavoUserBundle',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank(),
                new UserPassword([
                    'message' => 'enhavo_user.current_password.invalid',
                ]),
            ],
            'attr' => [
                'autocomplete' => 'current-password',
            ],
        ]);

        $builder->add('email', RepeatedType::class, [
            'type' => TextType::class,
            'options' => [
                'translation_domain' => 'EnhavoUserBundle',
                'attr' => [
                    'autocomplete' => 'new-password',
                ],
            ],
            'required' => false,
            'first_options' => [
                'label' => 'change_email.form.new_email',
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    new EmailNotExists(),
                ],
            ],
            'second_options' => [
                'label' => 'change_email.form.confirm_new_email',
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ],
            'invalid_message' => 'enhavo_user.change_email.mismatch',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangeEmail::class,
            'csrf_token_id' => 'change_email',
        ]);
    }
}
