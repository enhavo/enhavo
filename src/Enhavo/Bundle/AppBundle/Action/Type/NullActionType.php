<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:15
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NullActionType extends AbstractActionType implements ActionTypeInterface
{
    public function createViewData(array $options, Data $data, object $resource = null): void
    {

    }

    public function isEnabled(array $options, ResourceInterface $resource = null): bool
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
