<?php

namespace Enhavo\Bundle\ResourceBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormActionType extends AbstractActionType
{
    const TYPE_OPEN = 'open';
    const TYPE_DOWNLOAD = 'download';
    const TYPE_RELOAD = 'reload';


    public function createViewData(array $options, Data $data, ResourceInterface $resource = null): void
    {
        $data['saveLabel'] = $options['save_label'];
        $data['openRoute'] = $options['open_route'];
        $data['openRouteParameters'] = $options['open_route_parameters'];
        $data['openRouteMapping'] = $options['open_route_mapping'];
        $data['openType'] = $options['open_type'];
        $data['viewKey'] = $options['view_key'];
        $data['target'] = $options['target'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'file_document',
            'view_key' => 'form-view',
            'target' => '_view',
            'append_id' => true,
            'component' => 'form-action',
            'save_label' => 'enhavo_app.save',
            'open_route' => null,
            'open_route_parameters' => [],
            'open_route_mapping' => [],
            'open_type' => self::TYPE_OPEN,
        ]);

        $resolver->setRequired(['route', 'label']);
    }

    public static function getParentType(): ?string
    {
        return UrlActionType::class;
    }

    public static function getName(): ?string
    {
        return 'form';
    }
}
