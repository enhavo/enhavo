<?php


namespace Enhavo\Bundle\NewsletterBundle\Provider;


use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Provider\Type\ProviderType;
use Enhavo\Component\Type\AbstractType;

class AbstractProviderType extends AbstractType implements ProviderTypeInterface
{
    /** @var ProviderTypeInterface */
    protected $parent;

    public function getReceivers(NewsletterInterface $newsletter): array
    {
        return $this->parent->getReceivers($newsletter);
    }

    public function getTestReceivers(NewsletterInterface $newsletter): array
    {
        return $this->parent->getTestReceivers($newsletter);
    }

    public static function getParentType(): ?string
    {
        return ProviderType::class;
    }
}
