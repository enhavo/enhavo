<?php
/**
 * DoctrineSubscriber.php
 *
 * @since 03/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Enhavo\Bundle\TranslationBundle\Translator\LocaleResolver;
use Enhavo\Bundle\TranslationBundle\Translator\Translator;
use Doctrine\ORM\Event\OnFlushEventArgs;

class DoctrineSubscriber implements EventSubscriber
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
            'prePersist',
            'preUpdate',
            'preRemove',
            'postLoad',
            'preFlush'
        );
    }

    public function preFlush(PreFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach($uow->getIdentityMap() as $className) {
            foreach($className as $object) {
                $this->translator->store($object);
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->translator->store($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->translator->store($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->translator->delete($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->translator->translate($entity, $this->localeResolver->getLocale());
    }
}
