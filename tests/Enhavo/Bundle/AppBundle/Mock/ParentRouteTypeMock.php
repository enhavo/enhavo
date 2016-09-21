<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2/26/16
 * Time: 10:29 PM
 */

namespace Enhavo\Bundle\AppBundle\Mock;

use Enhavo\Bundle\AppBundle\Form\Type\RouteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

class ParentRouteTypeMock extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('route', new RouteType());
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\AppBundle\Mock\EntityMock',
        ));
    }

    public function getName()
    {
        return 'parent_route_mock';
    }
}