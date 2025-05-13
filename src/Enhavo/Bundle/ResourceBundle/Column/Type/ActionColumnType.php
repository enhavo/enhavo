<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionColumnType extends AbstractColumnType
{
    public function __construct(
        private readonly ActionManager $actionManager,
    ) {
    }

    public function createResourceViewData(array $options, object $resource, Data $data): void
    {
        $data->set('actions', $this->actionManager->createViewData($options['actions'], $resource));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-action',
            'model' => 'ActionColumn',
        ]);
        $resolver->setRequired(['actions']);
    }

    public static function getName(): ?string
    {
        return 'action';
    }
}
