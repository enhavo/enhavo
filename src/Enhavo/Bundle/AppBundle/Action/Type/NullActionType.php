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

class NullActionType extends AbstractActionType implements ActionTypeInterface
{
    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
    }

    public function isEnabled(array $options, ?object $resource = null): bool
    {
        return false;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => '',
            'model' => '',
            'label' => '',
        ]);
    }

    public static function getName(): ?string
    {
        return 'null';
    }
}
