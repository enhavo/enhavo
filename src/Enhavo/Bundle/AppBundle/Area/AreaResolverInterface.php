<?php

namespace Enhavo\Bundle\AppBundle\Area;

interface AreaResolverInterface
{
    public function resolve(): ?string;
}
