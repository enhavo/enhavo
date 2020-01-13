<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-08
 * Time: 22:03
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
