<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 9/10/16
 * Time: 1:09 PM
 */

namespace Enhavo\Bundle\ShopBundle\Mock;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use PHPUnit\Framework\TestCase;

class EventSubscriberMock implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [];
    }
}