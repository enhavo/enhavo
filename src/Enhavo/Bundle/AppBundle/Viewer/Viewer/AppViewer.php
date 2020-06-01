<?php
/**
 * AppViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppViewer extends BaseViewer
{
    public function getType()
    {
        return 'app';
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        parent::buildTemplateParameters($parameters, $requestConfiguration, $options);

        $data = [
            'view' => [
                'id' => $this->getViewId(),
                'label' => $this->container->get('translator')->trans($options['label'], [], $parameters->get('translation_domain'))
            ],
        ];

        $resourceData = $this->getResourceData($requestConfiguration, $options);
        if($resourceData) {
            $data['resource'] = $resourceData;
        }

        $parameters->set('data', $data);

        $parameters->set('actions', $this->mergeConfigArray([
            $options['actions'],
            $this->getViewerOption('actions', $requestConfiguration)
        ]));
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
            'actions' => [],
            'javascripts' => [
                'enhavo/view'
            ],
            'stylesheets' => [
                'enhavo/view'
            ],
            'label' => '',
            'template' => 'admin/view/app.html.twig'
        ]);
    }
}
