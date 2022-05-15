<?php

namespace Enhavo\Bundle\PaymentBundle\Payum\Offline;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;
use Payum\Offline\Constants;

final class StatusAction implements ActionInterface
{
    public function execute($request): void
    {
        /** @var GetStatusInterface $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        /** @psalm-suppress DocblockTypeContradiction */
        if (false === $model[Constants::FIELD_STATUS]) {
            $request->markNew();

            return;
        }

        if (Constants::STATUS_PENDING === $model[Constants::FIELD_STATUS]) {
            $request->markNew();

            return;
        }

        if (Constants::STATUS_AUTHORIZED === $model[Constants::FIELD_STATUS]) {
            $request->markAuthorized();

            return;
        }

        if (Constants::STATUS_CAPTURED === $model[Constants::FIELD_STATUS]) {
            $request->markCaptured();

            return;
        }

        if (Constants::STATUS_CANCELED === $model[Constants::FIELD_STATUS]) {
            $request->markCanceled();

            return;
        }

        $request->markUnknown();
    }

    public function supports($request): bool
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
