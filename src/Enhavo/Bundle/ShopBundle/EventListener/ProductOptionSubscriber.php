<?php
/**
 * UserSubscriber.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\ShopBundle\Entity\ProductOption;
use Enhavo\Bundle\ShopBundle\Manager\ProductManager;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductOptionSubscriber implements EventSubscriberInterface
{
    /** @var ProductManager */
    private $productManager;

    /** @var TranslationLocaleProviderInterface */
    private $translationLocaleProvider;

    public function __construct(ProductManager $productManager, TranslationLocaleProviderInterface $translationLocaleProvider)
    {
        $this->productManager = $productManager;
        $this->translationLocaleProvider = $translationLocaleProvider;
    }

    /**
     * {@inheritdoc}
     */
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
        if ($subject instanceof ProductOption) {
            if ($subject->getCode() === null) {
                $subject->setCode($this->productManager->generateCode($subject->getName()));
            }
            if ($subject->getPosition() === null) {
                $subject->setPosition(0);
            }

            foreach ($subject->getValues() as $value) {
                if ($value->getCode() === null) {
                    $value->setCurrentLocale($this->translationLocaleProvider->getDefaultLocaleCode());
                    $value->setCode($this->productManager->generateCode(sprintf('%s-%s', $subject->getName(), $value->getValue())));
                }
            }
        }
    }
}
