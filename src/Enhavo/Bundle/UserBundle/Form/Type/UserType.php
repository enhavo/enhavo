<?php
/**
 * UserType.php
 *
 * @since 04/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', array(
            'label' => 'user.form.label.username'
        ));

        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'form.password'),
            'second_options' => array('label' => 'form.password_confirmation'),
            'invalid_message' => 'fos_user.password.mismatch',
        ));

        $builder->add('email', 'text', array(
            'label' => 'user.form.label.email'
        ));

        $builder->add('firstName', 'text', array(
            'label' => 'user.form.label.firstName'
        ));

        $builder->add('lastName', 'text', array(
            'label' => 'user.form.label.lastName'
        ));

        $builder->add('groups', 'entity', array(
            'class' => 'EnhavoUserBundle:Group',
            'property' => 'name',
            'multiple' => true,
            'expanded' => true,
            'attr' => array('class' => 'category-list'),
            'label' => 'user.form.label.groups'
        ));
    }

    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'enhavo/UserBundle/Entity/User'
        );
    }

    public function getName()
    {
        return 'enhavo_user_user';
    }
}