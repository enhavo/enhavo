<?php

namespace Enhavo\Bundle\AppBundle\ErrorRenderer;

use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Symfony\Bridge\Twig\ErrorRenderer\TwigErrorRenderer;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class EnhavoErrorRenderer implements ErrorRendererInterface
{
    private ErrorRendererInterface $fallbackErrorRenderer;
    private \Closure $debug;

    public function __construct(
        private Environment $twigEnvironment,
        private string $projectDir,
        private TemplateResolver $templateResolver,
        ErrorRendererInterface $fallbackErrorRenderer,
        RequestStack $requestStack,
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
            return $exception;
        }

        $template = $this->getTemplate($exception->getStatusCode());

        return $exception->setAsString($this->twigEnvironment->render($template, [
            'exception' => $exception,
            'status_code' => $exception->getStatusCode(),
            'status_text' => $exception->getStatusText(),
        ]));
    }

    private function getTemplate(int $statusCode): string
    {
        $template = $this->templateResolver->resolve(sprintf('theme/error/%s.html.twig', $statusCode));
        if ($this->twigEnvironment->getLoader()->exists($template)) {
            return $template;
        }

        return $this->templateResolver->resolve('theme/error/default.html.twig');
    }
}
