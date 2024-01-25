<?php

namespace Enhavo\Bundle\UserBundle\Endpoint\Type\ChangePassword;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\VueFormBundle\Form\VueFormAwareInterface;
use Enhavo\Bundle\VueFormBundle\Form\VueFormAwareTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormEndpointType extends AbstractEndpointType implements VueFormAwareInterface
{
    use VueFormAwareTrait;

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $this->init($options, $request, $data, $context);

        $form = $this->getForm($options, $request, $data, $context);
        $context->set('form', $form);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            $url = null;
            if ($form->isSubmitted() && $form->isValid()) {
                $this->handleSuccess($options, $request, $data, $context, $form);

                $url = $this->getRedirectUrl($options, $request, $data, $context, $form);
                if ($url) {
                    if ($request->get('_format') === 'html') {
                        $context->setResponse($this->redirect($url));
                        return;
                    }
                    $data->set('redirect', $url);
                }

                $form =  $this->getForm($options, $request, $data, $context);
                $context->set('form', $form);
                $success = true;
            } else {
                $success = false;
                $this->handleFailed($options, $request, $data, $context, $form);
                $context->setStatusCode(400);
            }

            $data->set('success', $success);
            $data->set('redirect', $url);
        }

        $data->set('form', $this->createVueForm($form));

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
