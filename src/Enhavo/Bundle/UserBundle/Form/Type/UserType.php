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
        $builder->add('email', 'text', array(
            'label' => 'user.form.label.email',
            'translation_domain' => 'EnhavoUserBundle'
        ));

        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'form.password'),
            'second_options' => array('label' => 'form.password_confirmation'),
            'invalid_message' => 'fos_user.password.mismatch',
        ));

        $builder->add('firstName', 'text', array(
            'label' => 'user.form.label.firstName',
            'translation_domain' => 'EnhavoUserBundle'
        ));

        $builder->add('lastName', 'text', array(
            'label' => 'user.form.label.lastName',
            'translation_domain' => 'EnhavoUserBundle'
        ));

        $builder->add('admin', 'enhavo_boolean', array(
            'label' => 'user.form.label.admin',
            'translation_domain' => 'EnhavoUserBundle'
        ));

        $builder->add('groups', 'entity', array(
            'class' => 'EnhavoUserBundle:Group',
            'property' => 'name',
            'multiple' => true,
            'expanded' => true,
            'list' => true,
            'label' => 'user.form.label.groups',
            'translation_domain' => 'EnhavoUserBundle'
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