<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

interface VoucherInterface
{
    public function getId();

    public function getOrders(): array;

    public function getCode();

    public function setCode($code);

    public function getAmount(): int;

    public function setAmount($amount);

    public function getCreatedAt();

    public function setCreatedAt($createdAt);

    public function getAvailableAmount(): int;

    public function getRedeemedAmount(): int;

    public function isEnabled(): bool;

    public function setEnabled(bool $enabled): void;

    public function isPartialRedeemable(): bool;

    public function setPartialRedeemable(bool $partialRedeemable): void;

    public function getExpiredAt(): ?\DateTime;

    public function setExpiredAt(?\DateTime $expiredAt): void;

    public function getRedemptions(): Collection;

    public function addRedemption($redemption);

    public function removeRedemption($redemption);
}
