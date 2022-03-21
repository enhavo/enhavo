<?php

namespace Enhavo\Bundle\NewsletterBundle\Strategy;

use Enhavo\Bundle\NewsletterBundle\Event\SubscriberEvent;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Type\StrategyType;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractStrategyType extends AbstractType implements StrategyTypeInterface
{
    /** @var StrategyTypeInterface */
    protected $parent;

    /** @var TranslatorInterface */
    private $translator;

    /** @var EventDispatcher */
    private $eventDispatcher;

    public function addSubscriber(SubscriberInterface $subscriber, array $options)
    {
        return $this->parent->addSubscriber($subscriber, $options);
    }

    public function activateSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $this->parent->activateSubscriber($subscriber, $options);
    }

    public function removeSubscriber(SubscriberInterface $subscriber, array $options)
    {
        return $this->parent->removeSubscriber($subscriber, $options);
    }

    public function handleExists(SubscriberInterface $subscriber, array $options)
    {
        return $this->parent->handleExists($subscriber, $options);
    }

    public function getActivationTemplate(array $options): ?string
    {
        return $this->parent->getActivationTemplate($options);
    }

    public function getUnsubscribeTemplate(array $options): ?string
    {
        return $this->parent->getUnsubscribeTemplate($options);
    }

    public function setStorage(Storage $storage)
    {
        $this->parent->setStorage($storage);
    }

    public function getStorage(): Storage
    {
        return $this->parent->getStorage();
    }

    public static function getParentType(): ?string
    {
        return StrategyType::class;
    }

    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function setEventDispatcher(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function preAddSubscriber(SubscriberInterface $subscriber)
    {
        if ($this->eventDispatcher !== null) {
            $this->eventDispatcher->dispatch(new SubscriberEvent(SubscriberEvent::EVENT_PRE_ADD_SUBSCRIBER, $subscriber), SubscriberEvent::EVENT_PRE_ADD_SUBSCRIBER);
        } else {
            $this->parent->preAddSubscriber($subscriber);
        }
    }

    protected function postAddSubscriber(SubscriberInterface $subscriber)
    {
        if ($this->eventDispatcher !== null) {
            $this->eventDispatcher->dispatch(new SubscriberEvent(SubscriberEvent::EVENT_POST_ADD_SUBSCRIBER, $subscriber), SubscriberEvent::EVENT_POST_ADD_SUBSCRIBER);
        } else {
            $this->parent->postAddSubscriber($subscriber);
        }
    }

    protected function preActivateSubscriber(SubscriberInterface $subscriber)
    {
        if ($this->eventDispatcher !== null) {
            $this->eventDispatcher->dispatch(new SubscriberEvent(SubscriberEvent::EVENT_PRE_ACTIVATE_SUBSCRIBER, $subscriber), SubscriberEvent::EVENT_PRE_ACTIVATE_SUBSCRIBER);
        } else {
            $this->parent->preActivateSubscriber($subscriber);
        }
    }

    protected function postActivateSubscriber(SubscriberInterface $subscriber)
    {
        if ($this->eventDispatcher !== null) {
            $this->eventDispatcher->dispatch(new SubscriberEvent(SubscriberEvent::EVENT_POST_ACTIVATE_SUBSCRIBER, $subscriber), SubscriberEvent::EVENT_POST_ACTIVATE_SUBSCRIBER);
        } else {
            $this->parent->postActivateSubscriber($subscriber);
        }
    }

    protected function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        if ($this->translator !== null) {
            return $this->translator->trans($id, $parameters, $domain, $locale);
        }
        return $this->parent->trans($id, $parameters, $domain, $locale);
    }
}
