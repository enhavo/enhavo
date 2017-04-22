<?php
/**
 * DoctrineSubscriber.php
 *
 */

namespace Enhavo\Bundle\TranslationBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Enhavo\Bundle\TranslationBundle\Translator\LocaleResolver;
use Enhavo\Bundle\TranslationBundle\Translator\Translator;

/**
 * Class DoctrineTranslatorSubscriber
 * 
 * This subscriber is listing to doctrine events to call the translator actions
 *
 * @since 03/10/16
 * @author gseidel
 * @package Enhavo\Bundle\TranslationBundle\EventListener
 */
class DoctrineTranslatorSubscriber implements EventSubscriber
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var LocaleResolver
     */
    protected $localeResolver;

    /**
     * DoctrineSubscriber constructor.
     *
     * @param Translator $translator
     */
    public function __construct(Translator $translator, LocaleResolver $localeResolver)
    {
        $this->translator = $translator;
        $this->localeResolver = $localeResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            'preRemove',
            'postLoad',
            'preFlush'
        );
    }

    /**
     * Before flushing the data, we have to check if some translation data was stored for an object.
     *
     * @param PreFlushEventArgs $event
     */
    public function preFlush(PreFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        /*
         * We need to use the IdentityMap, because the update and persist collection stores entities, that have
         * computed changes, but translation data might have changed without changing it underlying model!
         */
        foreach($uow->getIdentityMap() as $className) {
            foreach($className as $object) {
                $this->translator->storeTranslationData($object);
            }
        }
    }

    /**
     * If entity will be deleted, we need to delete all its translation data as well
     * 
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->translator->deleteTranslationData($entity);
    }

    /**
     * Load TranslationData into to entity if it's fetched from the database
     * 
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->translator->translate($entity, $this->localeResolver->getLocale());
    }
}
