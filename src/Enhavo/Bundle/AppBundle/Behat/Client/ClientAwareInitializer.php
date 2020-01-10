<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-10
 * Time: 10:08
 */

namespace Enhavo\Bundle\AppBundle\Behat\Client;

use Behat\Behat\Context\Context;
use Enhavo\Bundle\AppBundle\Behat\Context\ClientAwareContext;
use Behat\Behat\Context\Initializer\ContextInitializer;

class ClientAwareInitializer implements ContextInitializer
{
    /**
     * @var ClientManager
     */
    private $clientManager;

    /**
     * Initializes initializer.
     *
     * @param ClientManager $clientManager
     */
    public function __construct(ClientManager $clientManager)
    {
        $this->clientManager = $clientManager;
    }

    /**
     * {@inheritdoc}
     */
    public function initializeContext(Context $context)
    {
        if (!$context instanceof ClientAwareContext) {
            return;
        }

        $context->setClientManager($this->clientManager);
    }
}
