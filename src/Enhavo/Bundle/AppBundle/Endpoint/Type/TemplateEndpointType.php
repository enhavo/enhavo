<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Endpoint\Data;
use Enhavo\Bundle\AppBundle\Endpoint\Loader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateEndpointType extends AbstractEndpointType
{
    public function __construct(
        private Loader $loader,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        if (is_array($options['load'])) {
            foreach ($options['load'] as $file) {
                $this->loader->merge($data, $this->loader->load($file), $options['recursive_merge'], $options['max_depth']);
            }
        } else if (is_string($options['load'])) {
            $this->loader->merge($data, $this->loader->load($options['load']), $options['recursive_merge'], $options['max_depth']);
        }

        if (is_array($options['data'])) {
            $this->loader->merge($data, $options['data'], $options['recursive_merge'], $options['max_depth']);
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
        ]);
    }

    public static function getName(): ?string
    {
        return 'template';
    }
}
