<?php

namespace Enhavo\Bundle\AppBundle\ErrorRenderer;

use Symfony\Bridge\Twig\ErrorRenderer\TwigErrorRenderer;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class EnhavoErrorRenderer implements ErrorRendererInterface
{
    const DEFAULT_TEMPLATE_REGEX = '@^(.*)/vendor/symfony/twig-bundle/Resources/views/Exception/error\d*.html.twig$@';

    /**
     * @var Environment
     */
    private $twigEnvironment;

    /**
     * @var ErrorRendererInterface
     */
    private $fallbackErrorRenderer;

    /**
     * @var string
     */
    private $projectDir;

    /**
     * @var callable
     */
    private $debug;

    public function __construct(Environment $twigEnvironment, ErrorRendererInterface $fallbackErrorRenderer = null, string $projectDir, RequestStack $requestStack, bool $debug)
    {
        $this->twigEnvironment = $twigEnvironment;
        $this->fallbackErrorRenderer = $fallbackErrorRenderer ?? new HtmlErrorRenderer();
        $this->projectDir = $projectDir;

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

        $template = $this->twigTemplate($exception->getStatusCode());
        $templatePath = $this->twigEnvironment->getLoader()->getSourceContext($template)->getPath();
        if (!$this->isDefaultTwigTemplatePath($templatePath)) {
            return $exception;
        }

        $template = $this->enhavoTemplate($exception->getStatusCode());

        return $exception->setAsString($this->twigEnvironment->render($template, [
            'exception' => $exception,
            'status_code' => $exception->getStatusCode(),
            'status_text' => $exception->getStatusText(),
        ]));
    }

    private function twigTemplate(int $statusCode): ?string
    {
        $template = sprintf('@Twig/Exception/error%s.html.twig', $statusCode);
        if ($this->twigEnvironment->getLoader()->exists($template)) {
            return $template;
        }

        $template = '@Twig/Exception/error.html.twig';
        if ($this->twigEnvironment->getLoader()->exists($template)) {
            return $template;
        }

        return null;
    }

    private function enhavoTemplate(int $statusCode): ?string
    {
        $template = sprintf('@EnhavoApp/theme/exception/error%s.html.twig', $statusCode);
        if ($this->twigEnvironment->getLoader()->exists($template)) {
            return $template;
        }

        $template = '@EnhavoApp/theme/exception/error.html.twig';
        if ($this->twigEnvironment->getLoader()->exists($template)) {
            return $template;
        }

        return null;
    }

    private function isDefaultTwigTemplatePath(string $templatePath)
    {
        if (preg_match(self::DEFAULT_TEMPLATE_REGEX, $templatePath, $matches)) {
            if ($matches[1] === $this->projectDir) {
                return true;
            }
        }
        return false;
    }
}
