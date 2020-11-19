<?php
/**
 * @author blutze-media
 * @since 2020-10-27
 */

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ResetPasswordRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', null, array('label' => 'registration.form.username', 'translation_domain' => 'EnhavoUserBundle'));
    }
}
