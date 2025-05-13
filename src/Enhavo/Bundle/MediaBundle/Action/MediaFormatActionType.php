<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaFormatActionType extends AbstractActionType
{
    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        $formats = [];
        if ($resource instanceof FormInterface) {
            $options = $resource->getConfig()->getOptions();
            if (isset($options['formats'])) {
                $formats = $options['formats'];
            }
        }

        $data->set('formats', $formats);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'media.form.action.format',
            'translation_domain' => 'EnhavoMediaBundle',
            'icon' => 'crop',
            'component' => 'action-media-format',
            'model' => 'MediaFormatAction',
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_format';
    }
}
