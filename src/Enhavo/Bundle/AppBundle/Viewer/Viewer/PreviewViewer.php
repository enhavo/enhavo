<?php
/**
 * PreviewViewer.php
 *
 * @since 12/04/19
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\AbstractActionViewer;
use FOS\RestBundle\View\View;
use Sylius\Component\Resource\Metadata\Metadata;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreviewViewer extends AbstractActionViewer
{
    public function getType()
    {
        return 'preview';
    }

    /**
     * {@inheritdoc}
     */
    public function createView($options = []): View
    {
        $view = parent::createView($options);
        $templateVars = $view->getTemplateData();
        $templateVars['data']['url'] = $this->container->get('router')->generate($this->getResourcePreviewUrl($options));
        $templateVars['data']['inputs'] = [];
        $view->setTemplateData($templateVars);
        return $view;
    }

    private function getResourcePreviewUrl($options)
    {
        /** @var Metadata $metadata */
        $metadata = $options['metadata'];
        $name = $metadata->getHumanizedName();
        $name = str_replace(' ', '_', $name);
        return sprintf('%s_%s_resource_preview', $metadata->getApplicationName(), $name);
    }

    protected function createActions($options)
    {
        $default = [
            'desktop' => [
                'type' => 'event',
                'label' => 'Desktop',
                'icon' => 'desktop_windows',
                'event' => 'desktop'
            ],
            'mobile' => [
                'type' => 'event',
                'label' => 'Mobile',
                'icon' => 'phone_iphone',
                'event' => 'mobile'
            ],
            'tablet' => [
                'type' => 'event',
                'label' => 'Tablet',
                'icon' => 'tablet_mac',
                'event' => 'tablet'
            ],
        ];

        return $default;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'javascripts' => [
                'enhavo/preview'
            ],
            'stylesheets' => [
                'enhavo/preview'
            ],
        ]);
        $optionsResolver->setRequired(['metadata']);
    }
}
