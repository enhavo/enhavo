<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Endpoint\Extension;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointTypeExtension;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Template\ExpressionLanguage\TemplateExpressionLanguageEvaluator;
use Enhavo\Bundle\AppBundle\Endpoint\Type\TemplateEndpointType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaRoutesEndpointExtensionType extends AbstractEndpointTypeExtension
{
    public function __construct(
        private readonly TemplateExpressionLanguageEvaluator $templateExpressionLanguageEvaluator,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        if ($options['media_routes']) {
            $routes = $data->has('routes') ? $data->get('routes') : [];

            if (!isset($routes['enhavo_media_theme_file'])) {
                $routes['enhavo_media_theme_file'] = $this->templateExpressionLanguageEvaluator->evaluate([
                    'path' => 'expr:url("/media/file/{id}")',
                ]);
            }

            if (!isset($routes['enhavo_media_theme_format'])) {
                $routes['enhavo_media_theme_format'] = $this->templateExpressionLanguageEvaluator->evaluate([
                    'path' => 'expr:url("/media/format/{id}/{format}")',
                ]);
            }

            $data->set('routes', $routes);
        }
    }

    public static function getExtendedTypes(): array
    {
        return [TemplateEndpointType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'media_routes' => false,
        ]);
    }
}
