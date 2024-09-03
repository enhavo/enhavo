<?php

namespace App\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Method;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\ViewEndpointType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/setting', name: 'app_theme_setting', defaults: ['_format' => 'json'])]
class SettingEndpoint extends AbstractEndpointType
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'settings' => true
        ]);
    }

    public static function getParentType(): ?string
    {
        return ViewEndpointType::class;
    }
}
