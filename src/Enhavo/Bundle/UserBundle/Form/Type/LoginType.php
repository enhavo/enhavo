<?php

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Enhavo\Bundle\UserBundle\Form\EventListener\CredentialsEventSubscriber;
use Enhavo\Bundle\UserBundle\Model\Credentials;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function __construct(
        private CredentialsEventSubscriber $credentialsEventSubscriber,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('userIdentifier', TextType::class, [
            'label' => $options['identifier_label'],
            'translation_domain' => $options['identifier_translation_domain'],
        ]);

        $builder->add('password', PasswordType::class, [
            'label' => 'security.login.password',
            'translation_domain' => 'EnhavoUserBundle'
        ]);

        $builder->add('csrfToken', HiddenType::class);

        $builder->add('rememberMe', CheckboxType::class, [
            'label' => 'security.login.remember_me',
            'translation_domain' => 'EnhavoUserBundle',
            'required' => false,
        ]);

        $builder->addEventSubscriber($this->credentialsEventSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'identifier_label' => 'security.login.email',
            'identifier_translation_domain' => 'EnhavoUserBundle',
            'data_class' => Credentials::class,
            'csrf_protection' => false,
        ]);
    }
}
