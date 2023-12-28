<?php

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
        private readonly TemplateExpressionLanguageEvaluator $templateExpressionLanguageEvaluator
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        if ($options['media_routes_enabled']) {

            $routes = $data->has('routes') ? $data->get('routes') : [];

            if (!isset($routes['enhavo_media_file_show'])) {
                $routes['enhavo_media_file_show'] = $this->templateExpressionLanguageEvaluator->evaluate([
                    'path' => 'expr:url("/file/show/{id}")',
                ]);
            }

            if (!isset($routes['enhavo_media_file_format'])) {
                $routes['enhavo_media_file_format'] = $this->templateExpressionLanguageEvaluator->evaluate([
                    'path' => 'expr:url("/file/format/{id}/{format}")',
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
            'media_routes_enabled' => false,
        ]);
    }
}
