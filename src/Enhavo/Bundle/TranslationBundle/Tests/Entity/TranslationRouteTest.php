<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Entity;


use Enhavo\Bundle\TranslationBundle\Entity\TranslationRoute;
use Enhavo\Bundle\TranslationBundle\Tests\Mocks\TranslatableMock;
use PHPUnit\Framework\TestCase;

class TranslationRouteTest extends TestCase
{
    public function testGettersSetters()
    {
        $route = new TranslationRoute();
        $route->setLocale('_de');
        $route->setProperty('_prop');
        $this->assertEquals('_de', $route->getLocale());
        $this->assertEquals('_prop', $route->getProperty());

    }
}
