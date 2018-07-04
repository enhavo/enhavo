<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 18:53
 */

namespace Enhavo\Bundle\NavigationBundle\Item\Configuration;

use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\NavigationBundle\Factory\NodeFactory;
use Enhavo\Bundle\NavigationBundle\Form\Type\NodeType;
use Enhavo\Bundle\NavigationBundle\Item\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'model' => Node::class,
            'form' => NodeType::class,
            'factory' => NodeFactory::class,
            'label' => 'node.label.node',
            'translationDomain' => 'EnhavoNavigationBundle',
            'template' => 'EnhavoNavigationBundle:Form:node.html.twig',
            'options' => []
        ]);
    }

    public function getType()
    {
        return 'node';
    }
}