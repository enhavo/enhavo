<?php

namespace Enhavo\Bundle\AppBundle\Behat\Context;

use Behat\Behat\Context\Context;
use Enhavo\Bundle\AppBundle\Behat\Context\KernelAwareContext;

/**
 * @author gseidel
 */
class KernelContext implements Context, KernelAwareContext
{
    use KernelAwareTrait;
}
