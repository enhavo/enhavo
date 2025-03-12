<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormEndpointType extends AbstractEndpointType
{
    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $this->init($options, $request, $data, $context);

        if ($context->getResponse() || $context->isStopped()) {
            return;
        }

        $form = $this->getForm($options, $request, $data, $context);
        $context->set('form', $form);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data->set('success', true);
                $this->handleSuccess($options, $request, $data, $context, $form);

                if ($context->getResponse() || $context->isStopped()) {
                    return;
                }

                $url = $this->getRedirectUrl($options, $request, $data, $context, $form);
                if ($url) {
                    if ($request->get('_format') === 'html') {
                        $context->setResponse($this->redirect($url));
                        return;
                    } else {
                        $data->set('redirect', $url);
                    }
                }

                $form =  $this->getForm($options, $request, $data, $context);
                $context->set('form', $form);
            } else {
                $data->set('success', false);
                $this->handleFailed($options, $request, $data, $context, $form);

                if ($context->getResponse() || $context->isStopped()) {
                    return;
                }

                $context->setStatusCode(400);
            }
        }

        $data->set('form', $this->normalize($form));
        $this->final($options, $request, $data, $context, $form);
    }

    protected function init($options, Request $request, Data $data, Context $context): void
    {

    }

    abstract protected function getForm($options, Request $request, Data $data, Context $context): FormInterface;

    abstract protected function handleSuccess($options, Request $request, Data $data, Context $context, FormInterface $form): void;

    protected function handleFailed($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {

    }

    protected function getRedirectUrl($options, Request $request, Data $data, Context $context, FormInterface $form): ?string
    {
        return null;
    }

    protected function final($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {

    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }
}
