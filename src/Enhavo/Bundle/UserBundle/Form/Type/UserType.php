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

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author gseidel
 */
class UserType extends AbstractType
{
    public function __construct(
        private readonly string $dataClass,
        private readonly string $groupDataClass,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly array $validationGroups = [],
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', TextType::class, [
            'label' => 'user.form.label.email',
            'translation_domain' => 'EnhavoUserBundle',
        ]);

        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'options' => ['translation_domain' => 'EnhavoUserBundle'],
            'first_options' => ['label' => 'form.password'],
            'second_options' => ['label' => 'form.password_confirmation'],
            'invalid_message' => 'enhavo_user.password.mismatch',
        ]);

        $builder->add('firstName', TextType::class, [
            'label' => 'user.form.label.firstName',
            'translation_domain' => 'EnhavoUserBundle',
        ]);

        $builder->add('lastName', TextType::class, [
            'label' => 'user.form.label.lastName',
            'translation_domain' => 'EnhavoUserBundle',
        ]);

        $builder->add('enabled', BooleanType::class, [
            'label' => 'user.form.label.enabled',
            'translation_domain' => 'EnhavoUserBundle',
        ]);

        $builder->add('verified', BooleanType::class, [
            'label' => 'user.form.label.verified',
            'translation_domain' => 'EnhavoUserBundle',
        ]);

        $builder->add('admin', BooleanType::class, [
            'label' => 'user.form.label.admin',
            'translation_domain' => 'EnhavoUserBundle',
            'help' => 'user.form.help.admin',
        ]);

        $builder->add('apiAccess', BooleanType::class, [
            'label' => 'user.form.label.api_access',
            'translation_domain' => 'EnhavoUserBundle',
        ]);

        if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $builder->add('superAdmin', BooleanType::class, [
                'label' => 'user.form.label.super_admin',
                'translation_domain' => 'EnhavoUserBundle',
            ]);
        }

        $builder->add('groups', EntityType::class, [
            'class' => $this->groupDataClass,
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true,
            'list' => true,
            'label' => 'user.form.label.groups',
            'translation_domain' => 'EnhavoUserBundle',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_user_user';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'validation_groups' => $this->validationGroups,
        ]);
    }
}
