<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.11.17
 */

namespace Enhavo\Bundle\ResourceBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionColumnType extends AbstractColumnType
{
    public function __construct(
        private readonly ActionManager $actionManager
    )
    {
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
