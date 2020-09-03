<?php

namespace Enhavo\Bundle\NewsletterBundle\Provider;

use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;

interface ProviderTypeInterface
{
    /**
     * @param NewsletterInterface $newsletter
     * @return Receiver[]
     */
    public function getReceivers(NewsletterInterface $newsletter): array;

    /**
     * @param NewsletterInterface $newsletter
     * @return Receiver[]
     */
    public function getTestReceivers(NewsletterInterface $newsletter): array;
}
