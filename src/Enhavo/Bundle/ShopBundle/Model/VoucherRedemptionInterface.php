<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Enhavo\Bundle\ShopBundle\Entity\Order;
use Enhavo\Bundle\ShopBundle\Entity\Voucher;

interface VoucherRedemptionInterface
{
    public function getId(): ?int;

    public function getVoucher(): ?Voucher;

    public function setVoucher(?Voucher $voucher): void;

    public function getOrder(): ?Order;

    public function setOrder(?Order $order): void;

    public function getAmount(): ?int;

    public function setAmount(?int $amount): void;

    public function getRedeemDate(): ?\DateTime;

    public function setRedeemDate(?\DateTime $redeemDate): void;
}
