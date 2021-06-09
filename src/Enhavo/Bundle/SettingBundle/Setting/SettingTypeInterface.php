<?php

namespace Enhavo\Bundle\SettingBundle\Setting;

use Enhavo\Component\Type\TypeInterface;

/**
 * BlockTypeInterface
 *
 * @author gseidel
 */
interface SettingTypeInterface extends TypeInterface
{
    public function init(array $options, $key = null);

    public function getValue(array $options, $key = null);

    public function getFormType(array $options, $key = null);

    public function getFormTypeOptions(array $options, $key = null);

    public function getViewValue(array $options, $value, $key = null);
}
