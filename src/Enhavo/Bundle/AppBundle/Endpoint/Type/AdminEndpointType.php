<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminEndpointType extends AbstractEndpointType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => '/admin/base.html.twig',
        ]);
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public static function getName(): ?string
    {
        return 'admin';
    }
}
