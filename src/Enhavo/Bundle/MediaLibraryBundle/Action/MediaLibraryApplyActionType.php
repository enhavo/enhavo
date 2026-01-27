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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class MediaLibraryApplyActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router,
    ) {
    }

    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        if ($options['update_route']) {
            $url = $this->getUrl($options['update_route'], $options['update_route_parameters'], $resource);
            $data->set('updateUrl', $url);
        }
        if ($options['apply_route']) {
            $url = $this->getUrl($options['apply_route'], $options['apply_route_parameters'], $resource);
            $data->set('applyUrl', $url);
        }
    }

    private function getUrl(string $route, array $routeParameters = [], ?object $resource = null): string
    {
        $parameters = [];
        if (null !== $resource && null !== $resource->getId()) {
            $parameters['id'] = $resource->getId();
        }
        $parameters = array_merge($parameters, $routeParameters);

        return $this->router->generate($route, $parameters);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'update_route_parameters' => [],
            'apply_route_parameters' => [],
            'label' => 'media_library.label.update',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
            'icon' => 'system_update_alt',
            'frame_key' => 'edit-view',
            'target' => '_frame',
            'confirm' => true,
            'confirm_message' => 'media_library.message.update.confirm',
            'confirm_label_ok' => 'media_library.label.update',
            'confirm_label_cancel' => 'media_library.label.cancel',
            'model' => 'MediaLibraryApplyAction',
        ]);

        $resolver->setRequired('update_route');
        $resolver->setRequired('apply_route');
    }

    public static function getName(): ?string
    {
        return 'media_library_apply';
    }
}
