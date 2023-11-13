<?php

namespace Enhavo\Bundle\MediaBundle\Endpoint\Extension;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointTypeExtension;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\TemplateEndpointType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaRoutesEndpointExtensionType extends AbstractEndpointTypeExtension
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        if ($options['media_routes']) {
            $data->set('routes', [
                'enhavo_media_file_show' => [
                    'path' => '/template/file/show/{id}',
                ],
                'enhavo_media_file_format' => [
                    'path' => '/template/file/format/{id}/{format}',
                ],
            ]);
        }
    }

    public static function getExtendedTypes(): array
    {
        return [TemplateEndpointType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'media_routes' => true,
        ]);
    }
}
