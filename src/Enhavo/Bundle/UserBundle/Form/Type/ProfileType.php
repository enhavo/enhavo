<?php
/**
 * ProfileType.php
 *
 * @since 23/09/16
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
        $builder->add('firstName', 'text', array(
            'label' => 'user.form.label.firstName',
            'translation_domain' => 'EnhavoUserBundle'
        ));

        $builder->add('lastName', 'text', array(
            'label' => 'user.form.label.lastName',
            'translation_domain' => 'EnhavoUserBundle'
        ));
    }

    public function getDefaultOptions()
    {
        return array(
            'data_class' => $this->dataClass
        );
    }

    public function getName()
    {
        return 'enhavo_user_profile';
    }
}