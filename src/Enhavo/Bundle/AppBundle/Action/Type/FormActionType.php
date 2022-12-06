<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractUrlActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormActionType extends AbstractUrlActionType
{
    const TYPE_OPEN = 'open';
    const TYPE_DOWNLOAD = 'download';
    const TYPE_RELOAD = 'reload';

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

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

    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);
        $data['saveLabel'] = $options['save_label'];
        $data['openRoute'] = $options['open_route'];
        $data['openRouteParameters'] = $options['open_route_parameters'];
        $data['openRouteMapping'] = $options['open_route_mapping'];
        $data['openType'] = $options['open_type'];
        $data['viewKey'] = $options['view_key'];
        $data['target'] = $options['target'];

        return $data;
    }

    public function getType()
    {
        return 'form';
    }
}
