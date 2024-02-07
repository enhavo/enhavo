<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Template\ExpressionLanguage\TemplateExpressionLanguageEvaluator;
use Enhavo\Bundle\AppBundle\Endpoint\Template\Loader;
use Enhavo\Bundle\AppBundle\Twig\TwigRouter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class TemplateEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly Loader $loader,
        private readonly TemplateExpressionLanguageEvaluator $templateExpressionLanguageEvaluator,
        private readonly TwigRouter $twigRouter,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $context->setStatusCode($options['status']);

        $this->loadData($options, $data);

        if (is_array($options['variants'])) {
            $this->loadVariants($request,  $data, $options['variants']);
        }

        if ($data->get('routes')) {
            $routes = $data->get('routes');
            $routeCollection = new RouteCollection();
            foreach ($routes as $name => $routeParameter) {
                $route = new Route($routeParameter['path']);
                $routeCollection->add($name, $route);
            }
            $this->twigRouter->addRouteCollection($routeCollection);
            $data->set('routes', $this->normalize($this->twigRouter->getRouteCollection(), null, ['groups' => 'endpoint']));
        }
    }

    private function loadData($options, Data $data): void
    {
        if ($options['load']) {
            $this->loader->merge($data, $this->loader->load($options['load']), $options['recursive'], $options['depth']);
        }

        if (is_array($options['data'])) {
            $templateData = $this->templateExpressionLanguageEvaluator->evaluate($options['data']);
            $this->loader->merge($data, $templateData, $options['recursive'], $options['depth']);
        }
    }

    public static function getParentType(): ?string
    {
        return ViewEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data' => null,
            'load' => null,
            'recursive' => false,
            'depth' => null,
            'description' => null,
            'variants' => null,
            'status' => 200,
        ]);
    }

    public static function getName(): ?string
    {
        return 'template';
    }

    private function loadVariants(Request $request, Data $data, $variants)
    {
        foreach ($variants as $condition => $options) {
            if ($this->checkVariantCondition($request, $condition)) {
                $resolver = new OptionsResolver();
                $resolver->setDefaults([
                    'data' => null,
                    'load' => null,
                    'recursive' => false,
                    'depth' => null,
                    'description' => null,
                ]);
                $options = $resolver->resolve($options);
                $this->loadData($options, $data);
            }
        }
    }

    private function checkVariantCondition(Request $request, $condition): bool
    {
        $parts = explode('=', $condition);
        if (count($parts) != 2) {
            throw new \Exception(sprintf('Variant condition need exactly one equal sign, %s given for condition \'%s\'', count($parts) - 1, $condition));
        }

        $key = $parts[0];
        $value = $parts[1];

        if ($request->query->has($key) && $request->query->get($key) == $value) {
            return true;
        } elseif ($request->attributes->has($key) && $request->attributes->get($key) == $value) {
            return true;
        }

        return false;
    }
}
