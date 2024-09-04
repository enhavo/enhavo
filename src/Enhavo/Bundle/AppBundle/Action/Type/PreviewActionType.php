<?php

/**
 * PreviewButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class PreviewActionType extends AbstractActionType
{
    public function __construct(
        private RouterInterface $router,
    )
    {
    }

    public function createViewData(array $options, Data $data, ResourceInterface $resource = null): void
    {
        $data->set('apiUrl', $this->router->generate($options['api_route'], [
            'id' => $resource->getId()
        ]));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'label.preview',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'remove_red_eye',
            'append_id' => true,
            'model' => 'PreviewAction',
            'component' => 'action-preview'
        ]);

        $resolver->setRequired(['route', 'api_route']);
    }

    public static function getParentType(): ?string
    {
        return UrlActionType::class;
    }

    public static function getName(): ?string
    {
        return 'preview';
    }
}
