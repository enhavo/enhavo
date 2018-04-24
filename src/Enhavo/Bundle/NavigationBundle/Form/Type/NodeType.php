<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.04.18
 * Time: 17:55
 */

namespace Enhavo\Bundle\NavigationBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\AbstractDynamicItemFormType;
use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeType extends AbstractDynamicItemFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Node::class
        ]);
    }

    public function getName()
    {
        return 'enhavo_navigation_node';
    }
}