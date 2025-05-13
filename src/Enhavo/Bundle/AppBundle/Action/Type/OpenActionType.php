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

class OpenActionType extends AbstractActionType
{
    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        $data->set('target', $options['target']);
        $data->set('frameKey', $options['frame_key']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'target' => '_frame',
            'frame_key' => null,
            'url' => null,
            'route' => null,
            'model' => 'OpenAction',
        ]);
    }

    public static function getParentType(): ?string
    {
        return UrlActionType::class;
    }

    public static function getName(): ?string
    {
        return 'open';
    }
}
