<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class MediaLibraryReplaceActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly RouteResolverInterface $routeResolver,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'sync',
            'label' => 'media_library.label.replace',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
            'model' => 'MediaLibraryReplaceAction',
            'component' => 'action-media-library-replace',
            'route' => null,
            'route_parameters' => [],
            'permission' => 'ROLE_ENHAVO_MEDIA_FILE_CREATE',
        ]);
    }

    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        if ($options['route']) {
            $url = $this->getUrl($options['route'], $options['route_parameters'], $resource);
        } else {
            $route = $this->routeResolver->getRoute('replace', ['api' => true]);

            if (null === $route) {
                throw new \Exception(sprintf('Can\'t resolve route for resource "%s". You have to explicit define the route.', get_class($resource)));
            }

            $url = $this->getUrl($route, $options['route_parameters'], $resource);
        }

        $updateRoute = $this->routeResolver->getRoute('update', ['api' => true]);
        $data->set('updateUrl', $this->getUrl($updateRoute, [], $resource));
        $data->set('url', $url);
    }

    private function getUrl(string $route, array $routeParameters = [], ?object $resource = null): string
    {
        $parameters = [];
        $parameters['id'] = $resource->getId();
        $parameters = array_merge_recursive($parameters, $routeParameters);

        return $this->router->generate($route, $parameters);
    }

    public static function getName(): ?string
    {
        return 'media_library_replace';
    }
}
