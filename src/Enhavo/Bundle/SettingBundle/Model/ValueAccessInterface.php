<?php

namespace Enhavo\Bundle\SettingBundle\Model;

interface ValueAccessInterface
{
    public function setValue($value);

    public function getValue();
}
