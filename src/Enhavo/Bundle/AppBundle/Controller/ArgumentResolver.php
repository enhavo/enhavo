<?php


namespace Enhavo\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\DefaultValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestAttributeValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\SessionValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\VariadicValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactory;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactoryInterface;

class ArgumentResolver implements ArgumentResolverInterface
{

    /** @var ArgumentMetadataFactory  */
    private $argumentMetadataFactory;

    public function __construct(ArgumentMetadataFactoryInterface $argumentMetadataFactory = null)
    {
        $this->argumentMetadataFactory = $argumentMetadataFactory ?: new ArgumentMetadataFactory();
    }

    /**
     * @inheritDoc
     */
    public function getArguments(Request $request, $controller): array
    {
        $arguments = [];
        $resolvers = $this->getDefaultArgumentValueResolvers();

        foreach ($this->argumentMetadataFactory->createArgumentMetadata($controller) as $metadata) {
            foreach ($resolvers as $resolver) {
                if (!$resolver->supports($request, $metadata)) {
                    continue;
                }

                $resolved = $resolver->resolve($request, $metadata);

                $atLeastOne = false;
                foreach ($resolved as $append) {
                    $atLeastOne = true;
                    $arguments[] = $append;
                }

                if (!$atLeastOne) {
                    $arguments[] = null;
                }

                // continue to the next controller argument
                continue 2;
            }
        }

        return $arguments;
    }

    /**
     * copied from Symfony\Component\HttpKernel\Controller\ArgumentResolver
     *
     * @return iterable
     */
    private function getDefaultArgumentValueResolvers(): iterable
    {
        return [
            new RequestAttributeValueResolver(),
            new RequestValueResolver(),
            new SessionValueResolver(),
            new DefaultValueResolver(),
            new VariadicValueResolver(),
        ];
    }
}
