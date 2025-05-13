<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Entity;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;

class SubscribeBlock extends AbstractBlock
{
    /** @var string|null */
    private $subscription;

    public function getSubscription(): ?string
    {
        return $this->subscription;
    }

    public function setSubscription(?string $subscription): void
    {
        $this->subscription = $subscription;
    }
}
