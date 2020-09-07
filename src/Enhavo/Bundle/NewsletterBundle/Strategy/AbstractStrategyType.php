<?php
/**
 * AbstractStrategyType.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Type\StrategyType;
use Enhavo\Component\Type\AbstractType;
use Twig\Environment;

abstract class AbstractStrategyType extends AbstractType implements StrategyTypeInterface
{
    /** @var StrategyTypeInterface */
    protected $parent;

    /** @var Environment */
    private $twig;

    /**
     * AbstractStrategyType constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function addSubscriber(SubscriberInterface $subscriber, array $options = [])
    {
        return $this->parent->addSubscriber($subscriber, $options);
    }

    public function handleExists(SubscriberInterface $subscriber, array $options = [])
    {
        return $this->parent->handleExists($subscriber, $options);
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

}
