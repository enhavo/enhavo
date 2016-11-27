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
use Enhavo\Bundle\GridBundle\Exception\NoTypeFoundException;
use Enhavo\Bundle\TranslationBundle\Translator\LocaleResolver;
use Enhavo\Bundle\TranslationBundle\Translator\Translator;

class DoctrineSubscriber implements EventSubscriber
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var Translator
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
            'postLoad'
        );
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
