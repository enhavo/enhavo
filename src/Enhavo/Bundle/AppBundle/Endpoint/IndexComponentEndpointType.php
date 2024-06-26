<?php

namespace Enhavo\Bundle\AppBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndexComponentEndpointType extends AbstractEndpointType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'resource-index'
        ]);
    }

    public static function getParentType(): ?string
    {
        return 'component';
    }
}
