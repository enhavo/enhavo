<?php
/**
 * UpdateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateViewType extends AbstractViewType
{
    public function getType()
    {
        return 'update';
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        parent::buildTemplateParameters($parameters, $requestConfiguration, $options);

        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];
        /** @var ResourceInterface $resource */
        $resource = $options['resource'];

        $parameters->set('form_action', $this->mergeConfig([
            sprintf('%s_%s_update', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            $options['form_action'],
            $this->getViewerOption('form.action', $requestConfiguration)
        ]));

        $parameters->set('form_action_parameters', $this->mergeConfigArray([
            [ 'id' => $resource->getId() ],
            $options['form_action_parameters'],
            $this->getViewerOption('form.action_parameters', $requestConfiguration)
        ]));

        $actionsSecondary = $this->mergeConfigArray([
            $this->createActionsSecondary($options, $requestConfiguration, $resource),
            $options['actions_secondary'],
            $this->getViewerOption('actions_secondary', $requestConfiguration)
        ]);

        $data = $parameters->get('data');
        $data['actionsSecondary'] = $this->actionManager->createActionsViewData($actionsSecondary, $options['resource']);

        $resourceData = $this->getResourceData($requestConfiguration, $options);
        if ($resourceData) {
            $data['resource'] = $resourceData;
        }

        $parameters->set('data', $data);
    }

    private function createActionsSecondary($options, $requestConfiguration, $resource)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        $formDelete = $this->mergeConfig([
            sprintf('%s_%s_delete', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            $options['form_delete'],
            $this->getViewerOption('form.delete', $requestConfiguration)
        ]);

        $formDeleteParameters = $this->mergeConfigArray([
            $options['form_delete_parameters'],
            $this->getViewerOption('form.delete_parameters', $requestConfiguration)
        ]);

        $default = [
            'delete' => [
                'type' => 'delete',
                'route' => $formDelete,
                'route_parameters' => $formDeleteParameters,
                'permission' => $this->getRoleNameByResourceName($metadata->getApplicationName(), $this->getUnderscoreName($metadata), 'delete')
            ]
        ];

        return $default;
    }

    private function getResourceData(RequestConfiguration $requestConfiguration, array $options)
    {
        $resourceData = null;
        $serializationGroups = $requestConfiguration->getSerializationGroups();
        if($serializationGroups && $options['resource']) {
            $resourceData = $this->container->get('serializer')->normalize($options['resource'], null, ['groups' => $serializationGroups]);
        }
        return $resourceData;
    }


    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'form_delete' => null,
            'form_delete_parameters' => [],
            'label' => 'label.edit'
        ]);
    }
}
