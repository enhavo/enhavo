<?php
/**
 * UpdateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateViewer extends CreateViewer
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

        $actions = $this->mergeConfigArray([
            $this->createActions($options, $requestConfiguration, $resource),
            $options['actions'],
            $this->getViewerOption('actions', $requestConfiguration)
        ]);

        $data = $parameters->get('data');
        $data['actions'] = $this->actionManager->createActionsViewData($actions, $options['resource']);
        $parameters->set('data', $data);
    }

    private function createActions($options, $requestConfiguration, $resource)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        $formDelete = $this->mergeConfig([
            sprintf('%s_%s_delete', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            $options['form_delete'],
            $this->getViewerOption('form.delete', $requestConfiguration)
        ]);

        $formDeleteParameters = $this->mergeConfigArray([
            [ 'id' => $resource->getId() ],
            $options['form_delete_parameters'],
            $this->getViewerOption('form.delete_parameters', $requestConfiguration)
        ]);

        $default = [
            'save' => [
                'type' => 'save',
                'route' => sprintf('%s_%s_create', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            ],
            'delete' => [
                'type' => 'delete',
                'route' => $formDelete,
                'route_parameters' => $formDeleteParameters
            ],
            'close' => [
                'type' => 'close'
            ]
        ];

        return $default;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'form_delete' => null,
            'form_delete_parameters' => [],
        ]);
    }
}
