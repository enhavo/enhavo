<?php
/**
 * UserType.php
 *
 * @since 04/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangeEmailType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('current_password', PasswordType::class, array(
            'label' => 'form.current_password',
            'translation_domain' => 'EnhavoUserBundle',
            'mapped' => false,
            'constraints' => new UserPassword(),
        ));

        $builder->add('email', RepeatedType::class, array(
            'type' => 'text',
            'options' => array('translation_domain' => 'EnhavoUserBundle'),
            'first_options' => array('label' => 'form.new_email'),
            'second_options' => array('label' => 'form.new_email_confirmation'),
            'invalid_message' => 'form.email.mismatch',
            'data' => ''
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'change_email',
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_user_change_email';
    }
}
