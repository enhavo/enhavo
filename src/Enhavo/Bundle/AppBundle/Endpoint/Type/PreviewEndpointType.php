<?php
/**
 * @since 18/11/15
 * @author gseidel
 */


namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Endpoint\Endpoint;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Input\Input;
use Enhavo\Bundle\ResourceBundle\Input\InputFactory;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreviewEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly InputFactory $inputFactory,
        private readonly FactoryInterface $endpointFactory,
        private readonly ResourceExpressionLanguage $expressionLanguage,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Input $input */
        $input = $this->inputFactory->create($options['input']);

        $resource = $input->getResource() ?? $input->createResource();

        $form = $input->getForm($resource);
        if ($form) {
            $form->setData($resource);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $resource = $form->getData();
                }
            }
        }

        /** @var Endpoint $endpoint */
        $endpoint = $this->endpointFactory->create($this->parseOption($options['endpoint'], $resource));
        $context->setResponse($endpoint->getResponse($request));
    }

    private function parseOption($endpointOptions, object $resource): array
    {
        foreach ($endpointOptions as $key => $value) {
            $endpointOptions[$key] = $this->expressionLanguage->evaluate($value, ['resource' => $resource]);
        }

        return $endpointOptions;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('endpoint');
        $resolver->setRequired('input');
    }

    public static function getName(): ?string
    {
        return 'preview';
    }
}
