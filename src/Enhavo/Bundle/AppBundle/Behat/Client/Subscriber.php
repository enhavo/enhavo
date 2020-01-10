<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-10
 * Time: 10:27
 */

namespace Enhavo\Bundle\AppBundle\Behat\Client;

use Behat\Testwork\EventDispatcher\Event\AfterSuiteTested;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Subscriber implements EventSubscriberInterface
{
    /**
     * @var ClientManager
     */
    private $clientManager;

    /**
     * Subscriber constructor.
     * @param ClientManager $clientManager
     */
    public function __construct(ClientManager $clientManager)
    {
        $this->clientManager = $clientManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            AfterSuiteTested::class => 'shutdown'
        );
    }

    public function shutdown()
    {
        $this->clientManager->stopWebServer();
    }
}
