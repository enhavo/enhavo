<?php

namespace Enhavo\Bundle\NavigationBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointTypeExtension;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\NavigationBundle\Repository\NavigationRepository;
use Symfony\Component\HttpFoundation\Request;

class NavigationEndpointExtension extends AbstractEndpointTypeExtension
{
    public function __construct(
        private NavigationRepository $navigationRepository,
    ) {}

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        if ($options['area'] !== 'theme') {
            return;
        }

        $navigations = $this->navigationRepository->findAll();

        $navigationData = [];
        foreach ($navigations as $navigation) {
            $navigationData[$navigation->getCode()] = $this->normalize($navigation, null, ['groups' => ['endpoint.navigation']]);
        }

        $data->set('navigation', $navigationData);
    }

    public static function getExtendedTypes(): array
    {
        return [AreaEndpointType::class];
    }
}
