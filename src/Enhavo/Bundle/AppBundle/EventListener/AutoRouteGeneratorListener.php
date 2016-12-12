<?php
/**
 * AutoRouteGeneratorListener.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Route\AutoGenerator;
use Enhavo\Bundle\AppBundle\Route\Routeable;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

class AutoRouteGeneratorListener
{
    /**
     * @var AutoGenerator
     */
    protected $autoGenerator;

    /**
     * @var AutoGenerator
     */
    protected $em;

    public function __construct(AutoGenerator $autoGenerator, EntityManagerInterface $em)
    {
        $this->autoGenerator = $autoGenerator;
        $this->em = $em;
    }

    public function onSave(ResourceControllerEvent $event)
    {
        $subject = $event->getSubject();

        if($subject instanceof Routeable) {
            $this->autoGenerator->generate($subject);
        }

        $this->em->flush();
    }
}