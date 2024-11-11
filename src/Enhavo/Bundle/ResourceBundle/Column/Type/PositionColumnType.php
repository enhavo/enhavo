<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\ResourceBundle\Column\Type;

use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PositionColumnType extends AbstractColumnType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-position',
            'model' => 'PositionColumn',
        ]);
    }

    public static function getName(): ?string
    {
        return 'position';
    }
}
