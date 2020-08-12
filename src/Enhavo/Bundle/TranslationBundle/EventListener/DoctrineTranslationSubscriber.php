<?php
/**
 * DoctrineSubscriber.php
 *
 */

namespace Enhavo\Bundle\TranslationBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Bundle\TranslationBundle\Translator\Text\AccessControl;
use Enhavo\Bundle\TranslationBundle\Translator\Text\TextTranslator;
use Enhavo\Component\Metadata\MetadataRepository;

/**
 * Class DoctrineTranslatorSubscriber
 *
 * This subscriber is listing to doctrine events to call the translator actions
 *
 * @since 03/10/16
 * @author gseidel
 * @package Enhavo\Bundle\TranslationBundle\EventListener
 */
class DoctrineTranslationSubscriber implements EventSubscriber
{
    /** @var TranslationManager */
    private $translationManager;

    /**
     * @var AccessControl
     */
    private $accessControl;

    /**
     * @var MetadataRepository
     */
    private $metadataRepository;

    /**
     * DoctrineTranslationSubscriber constructor.
     * @param TranslationManager $translationManager
     * @param AccessControl $accessControl
     * @param MetadataRepository $metadataRepository
     */
    public function __construct(TranslationManager $translationManager, AccessControl $accessControl, MetadataRepository $metadataRepository)
    {
        $this->translationManager = $translationManager;
        $this->accessControl = $accessControl;
        $this->metadataRepository = $metadataRepository;
    }


    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            'preRemove',
            'postLoad',
            'preFlush',
            'postFlush'
        );
    }

    /**
     * Before flushing the data, we have to check if some translation data was stored for an object.
     *
     * @param PreFlushEventArgs $event
     */
    public function preFlush(PreFlushEventArgs $event)
    {
        if (!$this->accessControl->isAccess()) {
            return;
        }

        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        /*
         * We need to use the IdentityMap, because the update and persist collection stores entities, that have
         * computed changes, but translation data might have changed without changing it underlying model!
         */
        foreach ($uow->getIdentityMap() as $className) {
            foreach ($className as $object) {
                if ($this->metadataRepository->hasMetadata($className)) {
                    $this->translationManager->detach($object);
                }
            }
        }
    }

    /**
     * Check if entity is not up to date an trigger flush again if needed
     *
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        if (!$this->accessControl->isAccess()) {
            return;
        }

        $uow = $args->getEntityManager()->getUnitOfWork();
        $result = $uow->getIdentityMap();

        foreach ($result as $class => $entities) {
            if ($this->metadataRepository->hasMetadata($class)) {
                foreach ($entities as $entity) {
                    $this->translationManager->translate($entity, $this->accessControl->getLocale());
                }
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
        if ($this->metadataRepository->hasMetadata($entity)) {
            $this->translationManager->delete($entity);
        }
    }

    /**
     * Load TranslationData into to entity if it's fetched from the database
     *
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        if (!$this->accessControl->isAccess()) {
            return;
        }

        $entity = $args->getEntity();
        if ($this->metadataRepository->hasMetadata($entity)) {
            $this->translationManager->translate($entity, $this->accessControl->getLocale());
        }
    }
}
