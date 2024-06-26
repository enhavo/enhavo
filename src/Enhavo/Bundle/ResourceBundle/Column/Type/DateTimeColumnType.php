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

class DateTimeColumnType extends AbstractColumnType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'format' => 'd.m.Y H:i',
        ]);
        $resolver->setRequired(['property']);
    }

    public static function getParentType(): ?string
    {
        return DateColumnType::class;
    }

    public static function getName(): ?string
    {
        return 'datetime';
    }
}
