<?php

namespace Enhavo\Bundle\NewsletterBundle\Strategy;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Type\StrategyType;
use Enhavo\Component\Type\AbstractType;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractStrategyType extends AbstractType implements StrategyTypeInterface
{
    /** @var StrategyTypeInterface */
    protected $parent;

    /** @var TranslatorInterface */
    private $translator;

    public function addSubscriber(SubscriberInterface $subscriber, array $options)
    {
        return $this->parent->addSubscriber($subscriber, $options);
    }

    public function activateSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $this->parent->activateSubscriber($subscriber, $options);
    }

    public function handleExists(SubscriberInterface $subscriber, array $options)
    {
        return $this->parent->handleExists($subscriber, $options);
    }

    public function getActivationTemplate(array $options): ?string
    {
        return $this->parent->getActivationTemplate($options);
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

    protected function getAdminSubject(array $options)
    {
        return $this->trans($options['admin_subject'], [], $options['translation_domain']);
    }

    protected function getSubject(array $options)
    {
        return $this->trans($options['subject'], [], $options['translation_domain']);
    }

    protected function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        if ($this->translator !== null) {
            return $this->translator->trans($id, $parameters, $domain, $locale);
        }
        return $this->parent->trans($id, $parameters, $domain, $locale);
    }
}
