<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormActionType extends AbstractActionType
{
    public const TYPE_OPEN = 'open';
    public const TYPE_DOWNLOAD = 'download';
    public const TYPE_RELOAD = 'reload';

    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        $data['saveLabel'] = $options['save_label'];
        $data['openRoute'] = $options['open_route'];
        $data['openRouteParameters'] = $options['open_route_parameters'];
        $data['openRouteMapping'] = $options['open_route_mapping'];
        $data['openType'] = $options['open_type'];
        $data['frameKey'] = $options['frame_key'];
        $data['target'] = $options['target'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'file_document',
            'frame_key' => 'form-view',
            'target' => '_frame',
            'append_id' => true,
            'save_label' => 'enhavo_app.save',
            'open_route' => null,
            'open_route_parameters' => [],
            'open_route_mapping' => [],
            'open_type' => self::TYPE_OPEN,
            'model' => 'FormAction',
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
