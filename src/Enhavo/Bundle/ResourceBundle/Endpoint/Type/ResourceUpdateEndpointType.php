<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Authorization\Permission;
use Enhavo\Bundle\ResourceBundle\Form\FormDescriber;
use Enhavo\Bundle\ResourceBundle\Form\FormNormalizerInterface;
use Enhavo\Bundle\ResourceBundle\Input\Input;
use Enhavo\Bundle\ResourceBundle\Input\InputFactory;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceUpdateEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly InputFactory $inputFactory,
        private readonly ResourceManager $resourceManager,
        private readonly FormNormalizerInterface $formNormalizer,
        private readonly FormDescriber $formDescriber,
        private readonly FormNormalizerInterface $formErrorNormalizer,
        private readonly FormNormalizerInterface $formDataNormalizer,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Input $input */
        $input = $this->inputFactory->create($options['input']);

        $resource = $input->getResource();

        if (null === $resource) {
            throw $this->createNotFoundException();
        }

        $this->denyAccessUnlessGranted(new Permission($input->getResourceName(), $options['permission']), $resource);

        $form = $input->createForm($resource);
        if ($form) {
            $form->handleRequest($request);

            $context->set('form', $form);
            $context->set('resource', $resource);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $this->resourceManager->save($resource);
                    $context->setStatusCode(200);
                    $form = $input->createForm($resource);
                } else {
                    $data->set('errors', $this->formErrorNormalizer->normalize($form));
                    $context->setStatusCode(400);
                }
            }

            $formFields = $request->query->get('form-fields') ? explode(',', $request->query->get('form-fields')) : null;
            $data->set('form', $this->formNormalizer->normalize($form, ['fields' => $formFields]));
            $data->set('data', $this->formDataNormalizer->normalize($form));
            $data->set('url', $request->getPathInfo());
        }

        $viewData = $input->getViewData($resource);
        $data->add($viewData);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'permission' => Permission::UPDATE,
        ]);

        $resolver->setRequired('input');
    }

    public function describe($options, Path $path): void
    {
        /** @var Input $input */
        $input = $this->inputFactory->create($options['input']);
        $form = $input->createForm();
        $schemaName = str_replace('\\', '', $form->getConfig()->getType()->getInnerType()::class);

        $this->formDescriber->describe($form, $path->getDocumentation()->components()->schema($schemaName));

        $path->method('get')
            ->tags([$input->getResourceName()])
            ->parameter('id')
                ->in('path')
                ->description('Id of resource')
                ->required(true)
                ->schema()
                    ->string()->end()
                ->end()
            ->end()
            ->parameter('form-fields')
                ->in('query')
                ->description('Comma separated list of form fields')
                ->schema()
                    ->string()->end()
                ->end()
            ->end()
            ->response('200')
                ->description('Data')
                ->content()
                    ->schema()
                        ->object()
                            ->property('actions', 'array')->items()->object()->end()->end()->end()
                            ->property('actionsSecondary', 'array')->items()->object()->end()->end()->end()
                            ->property('form', 'object')->end()
                            ->property('metadata', 'object')->end()
                            ->property('resource', 'object')->end()
                            ->property('tabs', 'object')->end()
                            ->property('url', 'string')->end()
        ;

        $path->method('post')
            ->tags([$input->getResourceName()])
            ->parameter('id')
                ->in('path')
                ->description('Id of resource')
                ->required(true)
                ->schema()
                    ->string()->end()
                ->end()
            ->end()
            ->parameter('form-fields')
                ->in('query')
                ->description('Comma separated list of form fields')
                ->schema()
                    ->string()->end()
                ->end()
            ->end()
            ->requestBody()
                ->content()
                    ->schema()
                        ->object()
                            ->property('data', 'object')->ref(sprintf('#/components/schemas/%s', $schemaName))->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->response('200')
                ->description('Resource updated')
                ->content()
                    ->schema()
                        ->object()
                            ->property('actions', 'array')->items()->object()->end()->end()->end()
                            ->property('actionsSecondary', 'array')->items()->object()->end()->end()->end()
                            ->property('form', 'object')->end()
                            ->property('metadata', 'object')->end()
                            ->property('resource', 'object')->end()
                            ->property('tabs', 'object')->end()
                            ->property('url', 'string')->end()
        ;
    }

    public static function getName(): ?string
    {
        return 'resource_update';
    }
}
