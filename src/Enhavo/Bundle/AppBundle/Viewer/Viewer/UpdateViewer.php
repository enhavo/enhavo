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

        $parameters->set('form_delete', $this->mergeConfig([
            sprintf('%s_%s_delete', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            $options['form_delete'],
            $this->getViewerOption('form.delete', $requestConfiguration)
        ]));

        $parameters->set('form_delete_parameters', $this->mergeConfigArray([
            [ 'id' => $resource->getId() ],
            $options['form_delete_parameters'],
            $this->getViewerOption('form.delete_parameters', $requestConfiguration)
        ]));

        $parameters->set('csrf_token', $this->container->get('security.csrf.token_manager')->getToken((string)$resource->getId())->getValue());

        $parameters->set('buttons', $this->mergeConfigArray([
            'buttons' => [
                'cancel' => [
                    'type' => 'cancel',
                ],
                'save' => [
                    'type' => 'save',
                ],
                'delete' => [
                    'type' => 'delete'
                ],
            ],
            $options['buttons'],
            $this->getViewerOption('buttons', $requestConfiguration)
        ]));

        $parameters->set('data', $options['resource']);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'form_delete' => null,
            'form_delete_parameters' => [],
            'template' => 'EnhavoAppBundle:Resource:update.html.twig',
        ]);
    }
}