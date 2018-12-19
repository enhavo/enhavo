<?php
/**
 * ProfileType.php
 *
 * @since 23/09/16
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    /**
     * ProfileType constructor.
     * 
     * @param $dataClass
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, array(
            'label' => 'user.form.label.firstName',
            'translation_domain' => 'EnhavoUserBundle'
        ));

        $builder->add('lastName', TextType::class, array(
            'label' => 'user.form.label.lastName',
            'translation_domain' => 'EnhavoUserBundle'
        ));
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'data_class' => $this->dataClass
        ]);
    }

    public function getName()
    {
        return 'enhavo_user_profile';
    }
}