<?php
/**
 * SettingResolveEvent.php
 *
 * @since 06/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\SettingBundle\Service;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\EventDispatcher\Event;

class SettingResolveEvent extends Event
{
    private $name;

    private $resolveCollection;

    public function __construct(Collection $resolveCollection, $name)
    {
        $this->resolveCollection = $resolveCollection;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function addResolver(SettingResolver $resolver)
    {
        $this->resolveCollection->add($resolver);
    }
} 