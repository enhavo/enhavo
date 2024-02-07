<?php

namespace Enhavo\Bundle\AppBundle\Profiler;

use Enhavo\Bundle\ApiBundle\Profiler\EndpointDataCollector;
use Enhavo\Bundle\AppBundle\Endpoint\Type\TemplateEndpointType;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class TemplateEndpointDataCollector extends AbstractDataCollector
{
    public function __construct(
        private readonly EndpointDataCollector $endpointDataCollector,
        private readonly RouterInterface $router,
    )
    {
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $this->data['variants'] = [];
        if ($this->hasTemplateEndpoint()) {
            $options = $this->endpointDataCollector->getOriginalOptions();
            if (is_array($options['variants'])) {
                $this->data['variants'][] = [
                    'condition' => 'Main',
                    'description' => null,
                    'link' => $this->generateMainLink($request),
                ];

                foreach ($options['variants'] as $condition => $options) {
                    $this->data['variants'][] = [
                        'condition' => $condition,
                        'description' => $options['description'] ?? null,
                        'link' => $this->generateVariantLink($request, $condition),
                    ];
                }
            }
        }
    }

    public function getVariants(): ?array
    {
        return $this->data['variants'];
    }

    private function generateMainLink(Request $request): ?string
    {
        try {
            $routeName = $request->attributes->get('_route');
            if ($routeName) {
                return $this->router->generate($routeName);
            }
        } catch (\Exception $e) {
        }

        return $request->getPathInfo();
    }

    private function generateVariantLink(Request $request, $condition): ?string
    {
        try {
            $routeName = $request->attributes->get('_route');
            $query = [];
            parse_str($condition, $query);
            if ($routeName) {
                return $this->router->generate($routeName, $query);
            }
        } catch (\Exception $e) {
        }

        return $request->getPathInfo() . '?' . $condition;
    }

    private function hasTemplateEndpoint(): bool
    {
        $nodes = $this->endpointDataCollector->getNodes();
        foreach ($nodes as $node) {
            if ($node->getType() === TemplateEndpointType::class) {
                return true;
            }
        }

        return false;
    }

    public function getName(): string
    {
        return 'template_endpoint';
    }

    public static function getTemplate(): ?string
    {
        return '@EnhavoApp/profiler/template_endpoint.html.twig';
    }
}
