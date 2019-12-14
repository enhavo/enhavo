<?php

namespace Enhavo\Bundle\NewsletterBundle\Provider;

use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;

interface ProviderInterface
{
    /**
     * @param NewsletterInterface $newsletter
     * @return Receiver[]
     */
    public function getReceivers(NewsletterInterface $newsletter): array;

    /**
     * @return array
     */
    public function getTestParameters();
}
