<?php
/**
 * CreateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Viewer\AbstractViewer;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateViewer extends AbstractViewer
{
    public function getType()
    {
        return 'create';
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        parent::buildTemplateParameters($parameters, $requestConfiguration, $options);

        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];
        /** @var Form $form */
        $form = $options['form'];

        $parameters->set('form', $form->createView());

        $parameters->set('tabs', $this->mergeConfigArray([
            [
                'general' => [
                    'label' => sprintf('%s.label.%s', $this->getUnderscoreName($metadata), $this->getUnderscoreName($metadata)),
                    'template' => 'EnhavoAppBundle:Tab:default.html.twig'
                ],
            ],
            $options['tabs'],
            $this->getViewerOption('tabs', $requestConfiguration)
        ]));

        $parameters->set('form_template', $this->mergeConfig([
            $options['form_template'],
            $this->getViewerOption('form.template', $requestConfiguration)
        ]));

        $parameters->set('form_action', $this->mergeConfig([
            sprintf('%s_%s_create', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            $options['form_action'],
            $this->getViewerOption('form.action', $requestConfiguration)
        ]));

        $parameters->set('form_action_parameters', $this->mergeConfigArray([
            $options['form_action_parameters'],
            $this->getViewerOption('form.action_parameters', $requestConfiguration)
        ]));

        $parameters->set('buttons', $this->mergeConfigArray([
            'buttons' => [
                'cancel' => [
                    'type' => 'cancel',
                ],
                'save' => [
                    'type' => 'save',
                ]
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
            'tabs' => [],
            'buttons' => [],
            'form' => null,
            'form_action' => null,
            'form_action_parameters' => [],
            'form_template' => 'EnhavoAppBundle:View:tab.html.twig',
            'template' => 'EnhavoAppBundle:Resource:create.html.twig'
        ]);
    }
}