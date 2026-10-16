<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Behat\Kernel;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Enhavo\Bundle\AppBundle\Behat\Context\KernelAwareContext;

class KernelAwareInitializer implements ContextInitializer
{
    public function __construct(
        private readonly KernelManager $kernelManager,
    ) {
    }

    public function initializeContext(Context $context): void
    {
        if (!$context instanceof KernelAwareContext) {
            return;
        }

        $context->setKernel($this->kernelManager->getKernel());
    }
}
