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
use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModalActionType extends AbstractActionType implements ActionTypeInterface
{
    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        $data['modal'] = $options['modal'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'model' => 'ModalAction',
        ]);

        $resolver->setRequired(['modal']);
    }

    public static function getName(): ?string
    {
        return 'modal';
    }
}
