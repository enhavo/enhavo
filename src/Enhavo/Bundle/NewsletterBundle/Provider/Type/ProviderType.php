<?php


namespace Enhavo\Bundle\NewsletterBundle\Provider\Type;


use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Provider\ProviderTypeInterface;
use Enhavo\Component\Type\AbstractType;

class ProviderType extends AbstractType implements ProviderTypeInterface
{
    public function getReceivers(NewsletterInterface $newsletter): array
    {
        return [];
    }

    public function getTestReceivers(NewsletterInterface $newsletter): array
    {
        return [];
    }

}
