<?php
/**
 * RoutingType.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class SlugType extends AbstractType
{
    public function getParent()
    {
        return TextType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_routing_slug';
    }
}