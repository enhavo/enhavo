<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndexComponentEndpointType extends AbstractEndpointType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'resource-index',
        ]);
    }

    public static function getParentType(): ?string
    {
        return 'component';
    }
}
