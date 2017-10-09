<?php

namespace Enhavo\Bundle\MediaBundle\Extension;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface ExtensionInterface extends TypeInterface
{
    public function renderExtension($options);

    public function renderButton($options);
}