<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 30.10.18
 * Time: 18:24
 */

namespace Enhavo\Bundle\ShopBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ShopBundle\Exception\VoucherValidationException;
use Enhavo\Bundle\ShopBundle\Model\AdjustmentInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\VoucherInterface;
use Enhavo\Bundle\ShopBundle\Model\VoucherRedemptionInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class VoucherManager
{
    public function __construct(
        private RepositoryInterface $repository,
        private ValidatorInterface $validator,
        private CartContextInterface $cartContext,
        private OrderProcessorInterface $orderProcessor,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private FactoryInterface $voucherRedemptionFactory,
    )
    {}

    public function update(VoucherInterface $voucher)
    {
        $voucher->getCode() === null;
        $voucher->setCode($this->generateCode());
    }

    public function remove(VoucherInterface $voucher)
    {
        /** @var OrderInterface $cart */
        $cart = $this->cartContext->getCart();

        $cart->removeVoucher($voucher);

        $this->orderProcessor->process($cart);
        $this->em->flush();
    }

    public function getVocher($code)
    {
        /** @var VoucherInterface $voucher */
        $voucher = $this->repository->findValidByCode($code);
        if ($voucher !== null && $voucher->getAvailableAmount() > 0) {
            return $voucher;
        }
        return null;
    }

    public function apply(VoucherInterface $voucher)
    {
        /** @var OrderInterface $cart */
        $cart = $this->cartContext->getCart();

        $errors = $this->validate($voucher, $cart);
        if (count($errors)) {
            throw new VoucherValidationException($errors[0]);
        }

        $cart->addVoucher($voucher);

        $this->orderProcessor->process($cart);
        $this->em->flush();
    }

    public function validate(VoucherInterface $voucher, OrderInterface $order)
    {
        $messages = [];
        $errors = $this->validator->validate($voucher, null, ['redeem']);
        if (count($errors)) {
            /** @var ConstraintViolationInterface $error */
            foreach($errors as $error) {
                $messages[] = $error->getMessage();
            }
        }

        if ($order->getVouchers()->contains($voucher)) {
            $messages[] = $this->translator->trans('voucher.message.already_applied', [], 'EnhavoShopBundle');
        }

        if (!$voucher->isPartialRedeemable() && $voucher->getAvailableAmount() > $order->getTotal()) {
            $messages[] = $this->translator->trans('voucher.message.amount_exceeded', [
                'amount' => $voucher->getAvailableAmount()
            ], 'EnhavoShopBundle');
        }

        return $messages;
    }

    public function isApplicable(VoucherInterface $voucher, OrderInterface $order)
    {
        count($this->validator->validate($voucher, $order)) === 0;
    }

    public function redeem(OrderInterface $order)
    {
        if ($order->getVouchers()->isEmpty()) {
            return;
        }

        $adjustments = $order->getAdjustments(AdjustmentInterface::VOUCHER_ADJUSTMENT);
        foreach ($adjustments as $adjustment) {
            /** @var VoucherInterface $voucher */
            $voucher = $this->repository->find($adjustment->getDetails()['voucher']);

            if ($voucher->getAvailableAmount() < abs($adjustment->getAmount())) {
                throw new VoucherValidationException('Can\'t redeem voucher. Not enough available money');
            }

            /** @var VoucherRedemptionInterface $voucherRedemption */
            $voucherRedemption = $this->voucherRedemptionFactory->createNew();
            $voucherRedemption->setAmount(abs($adjustment->getAmount()));
            $voucherRedemption->setOrder($order);
            $voucher->addRedemption($voucherRedemption);
        }

        $this->em->flush();
    }

    public function generateCode(): string
    {
        $code = '';
        $characters = [
            'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F',
            'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M', 0, 1, 2, 3, 4, 5, 6, 7, 8, 9
        ];
        for ($i = 0; $i < 10; $i++) {
            $code .= $characters[mt_rand(0, count($characters) - 1)];
        }

        if ($this->repository->findOneBy(['code' => $code])) {
            return $this->createCode();
        }

        return $code;
    }
}
