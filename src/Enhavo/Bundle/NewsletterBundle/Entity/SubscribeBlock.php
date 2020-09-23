<?php

namespace Enhavo\Bundle\NewsletterBundle\Entity;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;

class SubscribeBlock extends AbstractBlock
{
    /** @var string|null */
    private $subscription;

    /**
     * @return string|null
     */
    public function getSubscription(): ?string
    {
        return $this->subscription;
    }

    /**
     * @param string|null $subscription
     */
    public function setSubscription(?string $subscription): void
    {
        $this->subscription = $subscription;
    }


}
