<?php

namespace Enhavo\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\ShopBundle\Model\VoucherInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class Voucher implements ResourceInterface, VoucherInterface
{
    private ?int $id;
    private ?string $code;
    private ?int $amount;
    private bool $enabled = true;
    private bool $partialRedeemable = true;
    private ?\DateTime $createdAt;
    private ?\DateTime $expiredAt;
    /** @var Collection */
    private Collection $redemptions;

    public function __construct()
    {
        $this->redemptions = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOrders(): array
    {
        $orders = [];
        foreach ($this->redemptions as $redemption) {
            $orders[] = $redemption->getOrder();
        }
        return $orders;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getAmount(): int
    {
        return intval($this->amount);
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getAvailableAmount(): int
    {
        return $this->getAmount() - $this->getRedeemedAmount();
    }

    public function getRedeemedAmount(): int
    {
        $value = 0;
        foreach ($this->redemptions as $redemption) {
            $value += $redemption->getAmount();
        }
        return $value;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function isPartialRedeemable(): bool
    {
        return $this->partialRedeemable;
    }

    public function setPartialRedeemable(bool $partialRedeemable): void
    {
        $this->partialRedeemable = $partialRedeemable;
    }

    public function getExpiredAt(): ?\DateTime
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(?\DateTime $expiredAt): void
    {
        $this->expiredAt = $expiredAt;
    }

    public function getRedemptions(): ArrayCollection|Collection
    {
        return $this->redemptions;
    }

    public function addRedemption($redemption)
    {
        $this->redemptions->add($redemption);
        $redemption->setVoucher($this);
    }

    public function removeRedemption($redemption)
    {
        $this->redemptions->removeElement($redemption);
        $redemption->setVoucher(null);
    }
}
