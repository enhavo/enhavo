<?php

namespace Enhavo\Bundle\AppBundle\ErrorRenderer;

use Enhavo\Bundle\ApiBundle\Endpoint\Endpoint;
use Enhavo\Bundle\AppBundle\Endpoint\Type\ErrorEndpointType;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Bridge\Twig\ErrorRenderer\TwigErrorRenderer;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\RequestStack;

class EnhavoErrorRenderer implements ErrorRendererInterface
{
    private ErrorRendererInterface $fallbackErrorRenderer;
    private \Closure $debug;

    public function __construct(
        private readonly FactoryInterface $endpointFactory,
        private readonly RequestStack $requestStack,
        ErrorRendererInterface $fallbackErrorRenderer,
        bool $debug,
    ) {
        $this->fallbackErrorRenderer = $fallbackErrorRenderer ?? new HtmlErrorRenderer();
        $this->debug = TwigErrorRenderer::isDebug($requestStack, $debug);
    }

    /**
     * {@inheritdoc}
     */
    public function render(\Throwable $exception): FlattenException
    {
        $exception = $this->fallbackErrorRenderer->render($exception);

        $debug = ($this->debug)($exception);
        if ($debug) {
            $exception->setAsString($this->enhanceExceptionString($exception->getAsString()));
            return $exception;
        }

        /** @var Endpoint $errorEndpoint */
        $errorEndpoint = $this->endpointFactory->create([
            'type' => ErrorEndpointType::class,
            'exception' => $exception,
            'status_code' => $exception->getStatusCode(),
            'status_text' => $exception->getStatusText(),
        ]);

        $response = $errorEndpoint->getResponse($this->requestStack->getCurrentRequest());

        return $exception->setAsString($response->getContent());
    }

    private function enhanceExceptionString(string $content): string
    {
        $jsSnippet = file_get_contents(__DIR__.'/../Resources/assets/exception.js');
        $replaceContent = sprintf("<script type='application/javascript'>\n%s\n</script>\n", $jsSnippet);
        return str_replace('</body>', $replaceContent, $content);
    }
}
