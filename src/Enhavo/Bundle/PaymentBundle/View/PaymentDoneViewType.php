<?php

namespace Enhavo\Bundle\PaymentBundle\View;

use ApiViewType;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\PaymentBundle\Resolver\PaymentSubjectResolverInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentDoneViewType extends AbstractViewType
{
    public function __construct(
        private RepositoryInterface $repository,
        private PaymentSubjectResolverInterface $paymentSubjectResolver,
    )
    {}

    public function handleRequest($options, Request $request, ViewData $viewData, TemplateData $templateData)
    {
        $templateData['payment'] = null;
        $templateData['subject'] = null;

        if ($request->get('tokenValue')) {
            $token = $request->get('tokenValue');
            $payment = $this->repository->findOneBy([
                'token' => $token
            ]);

            if ($payment === null) {
                throw new NotFoundHttpException();
            }

            $templateData['subject'] = $this->paymentSubjectResolver->resolve($payment);
            $templateData['payment'] = $payment;
        }
    }

    public static function getParentType(): ?string
    {
        return ApiViewType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => 'theme/payment/done.html.twig',
            'request_configuration' => null
        ]);
    }

    public static function getName(): ?string
    {
        return 'payment_done';
    }
}
