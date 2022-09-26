<?php

namespace Enhavo\Bundle\UserBundle\Configuration;

interface ConfigKeyProviderInterface
{
    public function getConfigKey(): ?string;
}
