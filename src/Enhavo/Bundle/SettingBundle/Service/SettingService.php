<?php
/**
 * SettingService.php
 *
 * @since 06/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\SettingBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SettingService
{
    protected $dispatcher;

    protected $resolveCollection;

    protected $manager;

    public function __construct(ObjectManager $manager, EventDispatcherInterface $eventDispatcher)
    {
        $this->manager = $manager;
        $this->resolveCollection = new ArrayCollection();
        $this->dispatcher = $eventDispatcher;
    }

    public function resolve($name)
    {
        $event = new SettingResolveEvent($this->resolveCollection, $name);
        $this->dispatcher->dispatch('esperanto_setting.resolve', $event);

        if($this->resolveCollection->isEmpty()) {
            return null;
        }

        return $this->resolveCollection->get(0);
    }

    public function getSetting($name)
    {
        $setting = $this->manager->getRepository('esperantoSettingBundle:Setting')->findOneBy(array('name' => $name));
        if(!empty($setting)) {
            return $setting->getContainer();
        }
        return null;
    }
}