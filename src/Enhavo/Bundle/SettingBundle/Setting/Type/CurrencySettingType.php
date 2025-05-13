<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Enhavo\Bundle\FormBundle\Form\Type\CurrencyType;
use Enhavo\Bundle\FormBundle\Formatter\CurrencyFormatter;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencySettingType extends AbstractSettingType
{
    /** @var CurrencyFormatter */
    private $formatter;

    /**
     * CurrencySettingType constructor.
     */
    public function __construct(CurrencyFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public static function getName(): ?string
    {
        return 'currency';
    }

    public function getViewValue(array $options, $value, $key = null)
    {
        return $this->formatter->getCurrency((int) $value->getValue(), $options['currency'], $options['view_position']);
    }

    public static function getParentType(): ?string
    {
        return IntegerSettingType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'currency' => 'Euro',
            'view_position' => 'right',
            'form_type' => CurrencyType::class,
        ]);
    }
}
