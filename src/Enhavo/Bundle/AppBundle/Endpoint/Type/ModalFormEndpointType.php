<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModalFormEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly VueForm $vueForm,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $form = $this->formFactory->create($options['form'], null, $options['form_options']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $context->setStatusCode(400);
        }

        $data->set('form', $this->vueForm->createData($form->createView()));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'template' => 'admin/view/modal-form.html.twig',
            'form_options' => [],
        ]);

        $resolver->setRequired(['form']);
    }

    public static function getName(): ?string
    {
        return 'modal_form';
    }
}
