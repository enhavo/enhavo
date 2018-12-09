<?php
/**
 * IndexViewer.php
 *
 * @since 26/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use FOS\RestBundle\View\View;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndexViewer extends AppViewer
{
    public function getType()
    {
        return 'index';
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        parent::buildTemplateParameters($parameters, $requestConfiguration, $options);


        $parameters->set('blocks', $this->mergeConfigArray([
            $this->createBlock($options),
            $options['blocks'],
            $this->getViewerOption('blocks', $requestConfiguration)
        ]));

        $parameters->set('actions', $this->mergeConfigArray([
            $this->createActions($options),
            $options['actions'],
            $this->getViewerOption('actions', $requestConfiguration)
        ]));
    }

    private function createBlock($options)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        $default = [
            'table' => [
                'type' => 'table',
                'table_route' => sprintf('%s_%s_table', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
                'update_route' => sprintf('%s_%s_update', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            ]
        ];

        return $default;
    }

    private function createActions($options)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        $default = [
            'create' => [
                'type' => 'create',
                'route' => sprintf('%s_%s_create', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            ]
        ];

        return $default;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
    }
}