<?php
/**
 * UpdateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
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
        private readonly VueForm $vueForm,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Input $input */
        $input = $this->inputFactory->create($options['input']);

        $resource = $input->getResource();

        if ($resource === null) {
            throw $this->createNotFoundException();
        }

        $form = $input->getForm($resource);
        if ($form) {
            $form->handleRequest($request);

            $context->set('form', $form);
            $context->set('resource', $resource);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $this->resourceManager->create($resource);
                    $context->setStatusCode(200);
                } else {
                    $context->setStatusCode(400);
                }
            }
            $data->set('form', $this->vueForm->createData($form->createView()));
        }

        $viewData = $input->getViewData($resource);
        $data->add($viewData);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('input');
    }

    public static function getName(): ?string
    {
        return 'resource_update';
    }
}
