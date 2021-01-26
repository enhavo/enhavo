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
    public function init(array $options);

    public function getValue(array $options);

    public function getFormType(array $options);

    public function getFormTypeOptions(array $options);

    public function getViewValue(array $options, $value);
}
