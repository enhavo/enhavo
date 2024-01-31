<?php

namespace Enhavo\Bundle\PageBundle\ErrorRenderer;

use Enhavo\Bundle\PageBundle\Endpoint\PageEndpointType;
use Enhavo\Bundle\PageBundle\Repository\PageRepository;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Bridge\Twig\ErrorRenderer\TwigErrorRenderer;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\RequestStack;

class SpecialPageErrorRenderer implements ErrorRendererInterface
{
    private ErrorRendererInterface $fallbackErrorRenderer;
    private \Closure $debug;
    private RequestStack $requestStack;
    private FactoryInterface $endpointFactory;

    public function __construct(
        private PageRepository $pageRepository,
        ErrorRendererInterface $fallbackErrorRenderer,
        RequestStack $requestStack,
        bool $debug,
    ) {
        $this->fallbackErrorRenderer = $fallbackErrorRenderer ?? new HtmlErrorRenderer();
        $this->debug = TwigErrorRenderer::isDebug($requestStack, $debug);
        $this->requestStack = $requestStack;
    }

    public function setEndpointFactory(FactoryInterface $factory): void
    {
        $this->endpointFactory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function render(\Throwable $exception): FlattenException
    {
        $flattenException = FlattenException::createFromThrowable($exception);

        $debug = ($this->debug)($flattenException);
        if ($debug) {
            $this->fallbackErrorRenderer->render($exception);
        }

        try {
            $page = $this->getSpecialPage($flattenException->getStatusCode());
            if ($page) {
                return
                    $flattenException->setAsString(
                        $this->endpointFactory
                            ->create([
                                'type' => PageEndpointType::class,
                                'resource' => $page,
                                'preview' => false,
                                'area' => 'theme',
                            ])
                            ->getResponse($this->requestStack->getCurrentRequest())
                            ->getContent()
                    );
            }
        } catch (\Throwable $innerException) {}

        return $this->fallbackErrorRenderer->render($exception);
    }

    private function getSpecialPage(int $statusCode)
    {
        $specialPageKey = sprintf('error_%s', $statusCode);
        $page = $this->pageRepository->findOneBy([
            'code' => $specialPageKey,
            'public' => true,
        ]);
        if ($page) {
            return $page;
        }

        $page = $this->pageRepository->findOneBy([
            'code' => 'error_default',
            'public' => true,
        ]);
        if ($page) {
            return $page;
        }

        return null;
    }
}
