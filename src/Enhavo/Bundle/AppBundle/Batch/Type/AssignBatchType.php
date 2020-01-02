<?php

namespace Enhavo\Bundle\AppBundle\Batch\Type;

use Enhavo\Bundle\AppBundle\Batch\AbstractFormBatchType;
use Enhavo\Bundle\AppBundle\Exception\BatchExecutionException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * AssignBatchType.php
 *
 * @since 02/01/20
 * @author gseidel
 */
class AssignBatchType extends AbstractFormBatchType
{
    /**
     * @inheritdoc
     */
    public function execute(array $options, $resources)
    {
        $request = $this->container->get('request_stack')->getMasterRequest();
        $form = $this->container->get('form.factory')->create($options['form'], $options['form']);


        $translator = $this->container->get('translator');
        $form->handleRequest($request);
        if(!$form->isValid()) {
            throw new BatchExecutionException($translator->trans($options['error_assign'], [], $options['translation_domain']));
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $data = $form->getData();

        if($options['data_property']) {
            $data = $propertyAccessor->getValue($data, $options['data_property']);
        }

        $em = $this->container->get('doctrine.orm.entity_manager');
        foreach($resources as $resource) {
            $propertyAccessor->setValue($resource, $options['property'], $data);
        }
        $em->flush();
    }

    /**
     * @inheritdoc
     */
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);
        $data['route'] = $options['route'];
        $data['routeParameters'] = $options['route_parameters'];
        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'batch.assign.label',
            'translation_domain' => 'EnhavoAppBundle',
            'route' => null,
            'route_parameters' => null,
            'form_parameters' => [],
            'error_assign' => 'batch.assign.error.assign',
            'data_property' => null
        ]);

        $resolver->setRequired(['form', 'property']);
    }

    public function getType()
    {
        return 'assign';
    }
}
