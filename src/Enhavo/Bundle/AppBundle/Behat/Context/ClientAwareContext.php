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

interface ClientAwareContext
{
    public function setClientManager(ClientManager $manager);

    public function getClient(): Client;
}
