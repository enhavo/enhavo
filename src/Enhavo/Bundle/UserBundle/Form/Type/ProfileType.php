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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author gseidel
 */
class ProfileType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    /**
     * ProfileType constructor.
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, [
            'label' => 'user.form.label.firstName',
            'translation_domain' => 'EnhavoUserBundle',
        ]);

        $builder->add('lastName', TextType::class, [
            'label' => 'user.form.label.lastName',
            'translation_domain' => 'EnhavoUserBundle',
        ]);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'data_class' => $this->dataClass,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_user_profile';
    }
}
