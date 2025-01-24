<?php

namespace Enhavo\Bundle\ResourceBundle\Form;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Util\ServerParams;
use Symfony\Component\HttpFoundation\Request;

/**
 * Copied and modified from ElaoJsonHttpFormBundle
 */
class JsonHttpFoundationRequestHandler extends HttpFoundationRequestHandler
{
    private const BODY_METHODS = ['POST', 'PUT', 'PATCH', 'DELETE'];

    private ServerParams $serverParams;

    public function __construct(?ServerParams $serverParams = null)
    {
        parent::__construct($serverParams);

        $this->serverParams = $serverParams ?: new ServerParams();
    }

    /**
     * @param mixed|null $request Support old versions of RequestHandlerInterface
     */
    public function handleRequest(FormInterface $form, mixed $request = null): void
    {
        if (!$request instanceof Request) {
            throw new UnexpectedTypeException($request, Request::class);
        }
        if (
            'json' === $request->getContentTypeFormat()
            && \in_array($request->getMethod(), self::BODY_METHODS, false)
        ) {
            $this->handleJsonRequest($form, $request);

            return;
        }

        parent::handleRequest($form, $request);
    }

    protected function handleJsonRequest(FormInterface $form, Request $request): void
    {
        if (!$this->serverParams->hasPostMaxSizeBeenExceeded()) {
            $name = $form->getName();
            $content = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $form->submit(null, false);
                $form->addError(
                    new FormError(
                        sprintf(
                            'The given JSON content could not be parsed: %s',
                            json_last_error_msg()
                        )
                    )
                );

                return;
            }

            if ('' === $name || 'DELETE' === $request->getMethod() || !\is_array($content)) {
                $data = $content;
            } else {
                // Don't submit if the form's name does not exist in the request
                if (!isset($content[$name])) {
                    return;
                }

                $data = $content[$name];
            }

            $form->submit($data, 'PATCH' !== $request->getMethod());
        }
    }
}
