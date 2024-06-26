<?php

namespace Enhavo\Bundle\ShopBundle\Entity;

use Enhavo\Bundle\ShopBundle\Model\VoucherRedemptionInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;

class VoucherRedemption implements ResourceInterface, VoucherRedemptionInterface
{
    private ?int $id;
    private ?Voucher $voucher;
    private ?Order $order;
    private ?int $amount;
    private ?\DateTime $redeemDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVoucher(): ?Voucher
    {
        return $this->voucher;
    }

    public function setVoucher(?Voucher $voucher): void
    {
        $this->voucher = $voucher;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): void
    {
        $this->order = $order;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }

    public function getRedeemDate(): ?\DateTime
    {
        return $this->redeemDate;
    }

    public function setRedeemDate(?\DateTime $redeemDate): void
    {
        $this->redeemDate = $redeemDate;
    }
}
