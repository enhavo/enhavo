<?php


namespace Enhavo\Bundle\NewsletterBundle\Provider;


use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Component\Type\AbstractContainerType;

class Provider extends AbstractContainerType
{
    /** @var ProviderTypeInterface */
    protected $type;

    public function getReceivers(NewsletterInterface $newsletter): array
    {
        return $this->type->getReceivers($newsletter);
    }

    public function getTestReceivers(NewsletterInterface $newsletter): array
    {
        return $this->type->getTestReceivers($newsletter);
    }
}
