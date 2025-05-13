<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Behat\Client;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Enhavo\Bundle\AppBundle\Behat\Context\ClientAwareContext;

class ClientAwareInitializer implements ContextInitializer
{
    /**
     * @var ClientManager
     */
    private $clientManager;

    /**
     * Initializes initializer.
     */
    public function __construct(ClientManager $clientManager)
    {
        $this->clientManager = $clientManager;
    }

    public function initializeContext(Context $context)
    {
        if (!$context instanceof ClientAwareContext) {
            return;
        }

        $context->setClientManager($this->clientManager);
    }
}
