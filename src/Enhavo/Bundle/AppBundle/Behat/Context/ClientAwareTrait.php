<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Behat\Context;

use Enhavo\Bundle\AppBundle\Behat\Client\ClientManager;
use Symfony\Component\Panther\Client;

trait ClientAwareTrait
{
    /**
     * @var ClientManager
     */
    protected $clientManager;

    public function setClientManager(ClientManager $manager)
    {
        $this->clientManager = $manager;
    }

    public function getClient(): Client
    {
        return $this->clientManager->getClient();
    }
}
