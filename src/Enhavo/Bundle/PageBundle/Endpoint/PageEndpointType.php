<?php

namespace Enhavo\Bundle\PageBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\PageBundle\Model\PageInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageEndpointType extends AbstractEndpointType
{
    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var PageInterface $resource */
        $resource = $options['resource'];
        if (!$resource->isPublished() && !$options['preview']) {
            throw $this->createNotFoundException();
        }

        $data->set('resource', $this->normalize($resource, null, ['groups' => ['endpoint']]));
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'preview' => false,
            'template' => '{{ area }}/resource/page/show.html.twig'
        ]);
        $resolver->setRequired('resource');
    }
}
