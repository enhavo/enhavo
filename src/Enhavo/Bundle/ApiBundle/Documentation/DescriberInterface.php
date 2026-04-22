<?php

namespace Enhavo\Bundle\ApiBundle\Documentation;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Documentation;

interface DescriberInterface
{
    public function describe(Documentation $documentation, array $options = []): void;
}
