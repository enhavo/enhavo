<?php

namespace Enhavo\Bundle\TranslationBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Authorization\Permission;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TranslateResourceEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly ResourceManager $resourceManager,
        private readonly RouteResolverInterface $routeResolver,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslationManager $translationManager,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $metadata = $this->resourceManager->getMetadata($options['resource']);
        $repository = $this->resourceManager->getRepository($options['resource']);

        $id = intval($request->query->get('id'));
        if (!$id) {
            $context->setStatusCode(404);
            return;
        }

        $resource = $repository->find($id);
        if ($resource === null) {
            $context->setStatusCode(404);
            return;
        }

        if ($options['permission']) {
            $this->denyAccessUnlessGranted(new Permission($metadata->getName(), $options['permission']), $resource);
        }

        foreach ($this->translationManager->getLocales() as $locale) {
            if ($this->translationManager->getDefaultLocale() === $locale) {
                continue;
            }
            $this->translationManager->applyAutoTranslation($resource, $locale, null, $resource);
        }

        $this->resourceManager->save($resource);

        $updateRoute = $options['update_route'] ?? $this->routeResolver->getRoute('update', ['api' => true]);
        $url = $this->urlGenerator->generate($updateRoute, ['id' => $id]);
        $context->setResponse(new RedirectResponse($url));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            'resource',
        ]);

        $resolver->setDefaults([
            'permission' => null,
            'update_route' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'translate_resource';
    }
}
