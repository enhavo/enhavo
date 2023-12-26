<?php

namespace Enhavo\Bundle\PageBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Method;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\PageBundle\Model\PageInterface;
use Enhavo\Bundle\PageBundle\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly PageRepository $repository,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var PageInterface $resource */
        $resource = $options['resource'];

        if ($resource === null) {
            $id = intval($request->get('id'));
            $resource = $this->repository->find($id);
        }

        if ($resource === null) {
            throw $this->createNotFoundException();
        }

        if (!$resource->isPublished() && !$options['preview']) {
            throw $this->createNotFoundException();
        }

        $context->set('resource', $resource);
        $data->set('resource', $this->normalize($resource, null, ['groups' => ['endpoint']]));
    }

    public function describe($options, Path $path)
    {
        $path
            ->method(Method::GET)
                ->description('Page data')
                ->summary('Get single page data')
                ->parameter('id')
                    ->in('path')
                    ->description('The page id')
                    ->required(true)
                ->end()
            ->end()
        ;
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'preview' => false,
            'template' => '{{ area }}/resource/page/show.html.twig',
            'resource' => null,
            'vue_routes_enabled' => true,
            'vue_routes_groups' => ['theme'],
        ]);
    }
}
