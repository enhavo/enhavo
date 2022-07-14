<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\ShopBundle\Exception\VoucherValidationException;
use Enhavo\Bundle\ShopBundle\Manager\VoucherManager;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class VoucherController extends AbstractController
{
    public function __construct(
        private VoucherManager $voucherManager,
        private CartContextInterface $cartContext,
        private NormalizerInterface $normalizer,
    )
    {}

    public function addAction(Request $request)
    {
        $voucher = $this->getVoucher($request);

        try {
            $this->voucherManager->apply($voucher);
        } catch (VoucherValidationException $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }

        return new JsonResponse([
            'cart' => $this->normalizer->normalize($this->cartContext->getCart(), null, [
                'groups' => ['cart']
            ])
        ]);
    }

    private function getVoucher(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        if (!isset($content['code'])) {
            throw $this->createNotFoundException();
        }

        $voucher = $this->voucherManager->getVocher($content['code']);
        if ($voucher === null) {
            throw $this->createNotFoundException();
        }

        return $voucher;
    }

    public function removeAction(Request $request)
    {
        $voucher = $this->getVoucher($request);

        try {
            $this->voucherManager->remove($voucher);
        } catch (VoucherValidationException $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }

        return new JsonResponse([
            'cart' => $this->normalizer->normalize($this->cartContext->getCart(), null, [
                'groups' => ['cart']
            ])
        ]);
    }
}
