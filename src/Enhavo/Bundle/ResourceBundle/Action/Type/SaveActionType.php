<?php

/**
 * CancelButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class SaveActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router
    )
    {
    }

    public function createViewData(array $options, Data $data, ResourceInterface $resource = null): void
    {
        $url = null;
        if ($options['route']) {
            $url = $this->getUrl($options, $resource);
        }

        $data->set('url', $url);
    }

    private function getUrl(array $options, ResourceInterface $resource = null): string
    {
        $parameters['id'] = $resource->getId();
        $parameters = array_merge_recursive($parameters, $options['route_parameters']);
        return $this->router->generate($options['route'], $parameters);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'save-action',
            'label' => 'label.save',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'save',
            'route' => null,
            'route_parameters' => [],
        ]);
    }

    public static function getName(): ?string
    {
        return 'save';
    }
}
