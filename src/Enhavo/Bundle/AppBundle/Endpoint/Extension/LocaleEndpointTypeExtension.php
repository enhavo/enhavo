<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Endpoint\Extension;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointTypeExtension;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\ViewEndpointType;
use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocaleEndpointTypeExtension extends AbstractEndpointTypeExtension
{
    public function __construct(
        private LocaleResolverInterface $localeResolver
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        if (is_string($options['locale'])) {
            $data->set('locale', $options['locale']);
        } else if($options['locale'] === true) {
            $data->set('locale', $this->localeResolver->resolve());
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'locale' => null,
        ]);
    }

    public static function getExtendedTypes(): array
    {
        return [ViewEndpointType::class];
    }
}
