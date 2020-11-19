<?php
/**
 * @author blutze-media
 * @since 2020-10-27
 */

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangeEmailRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraintsOptions = [
            'message' => 'enhavo_user.current_password.invalid',
        ];

        if (!empty($options['validation_groups'])) {
            $constraintsOptions['groups'] = [reset($options['validation_groups'])];
        }

        $builder->add('current_password', PasswordType::class, [
            'label' => 'form.current_password',
            'translation_domain' => 'EnhavoUserBundle',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank(),
                new UserPassword($constraintsOptions),
            ],
            'attr' => [
                'autocomplete' => 'current-password',
            ],
        ]);

        $builder->add('email', RepeatedType::class, array(
            'type' => TextType::class,
            'options' => array(
                'translation_domain' => 'EnhavoUserBundle',
                'attr' => array(
                    'autocomplete' => 'new-password',
                ),
            ),
            'required' => false,
            'first_options' => [
                'label' => 'change_email.form.new_email',
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                    new Email(),
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
        ));
    }
}
