<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Loader;
use Enhavo\Bundle\AppBundle\Twig\TwigRouter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly Loader $loader,
    )
    {
    }

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

        if (is_array($options['load'])) {
            foreach ($options['load'] as $file) {
                $this->loader->merge($data, $this->loader->load($file), $options['recursive'], $options['depth']);
            }
        } else if (is_string($options['load'])) {
            $this->loader->merge($data, $this->loader->load($options['load']), $options['recursive'], $options['depth']);
        }

        if (is_array($options['data'])) {
            $this->loader->merge($data, $options['data'], $options['recursive'], $options['depth']);
        }
    }

    public static function getParentType(): ?string
    {
        return ViewEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data' => null,
            'load' => null,
            'recursive' => false,
            'depth' => null,
            'description' => null,
            'media_routes' => true,
        ]);
    }

    public static function getName(): ?string
    {
        return 'template';
    }
}
