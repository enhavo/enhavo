<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 16.01.18
 * Time: 14:54
 */

namespace Enhavo\Bundle\AppBundle\Form\Extension;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityTypeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'list' => false
        ]);
    }

    public function getExtendedType()
    {
        return EntityType::class;
    }
}