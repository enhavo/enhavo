<?php

namespace Enhavo\Bundle\TaxonomyBundle\EventListener;

use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;
use Enhavo\Bundle\TaxonomyBundle\Taxonomy\TaxonomyManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TermSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private TaxonomyManager $taxonomyManager,
    )
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            ResourceEvents::PRE_CREATE => 'onPreSave',
            ResourceEvents::PRE_UPDATE => 'onPreSave',
        ];
    }

    public function onPreSave(ResourceEvent $event)
    {
        $subject = $event->getSubject();
        if ($subject instanceof Term) {
            $this->taxonomyManager->updateTerm($subject);
        }
    }
}
