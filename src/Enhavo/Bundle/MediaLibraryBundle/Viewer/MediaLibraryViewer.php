<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\AbstractActionViewer;
use FOS\RestBundle\View\View;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibraryViewer extends AbstractActionViewer
{
    /**
     * {@inheritdoc}
     */
    public function createView($options = []): View
    {
        $view = parent::createView($options);

        $templateVars = $view->getTemplateData();
        $templateVars['data']['items'] = $options['items'];
        $templateVars['data']['data'] = $options['data'];
        $templateVars['data']['data']['tabs'] = $this->getTabs();
        $templateVars['data']['data']['multiple'] = $options['multiple'];
        $templateVars['data']['data']['tags'] = $options['tags'];
        $templateVars['data']['messages'] = [];

        $view->setTemplateData($templateVars);

        return $view;
    }

    private function getTabs()
    {
        $data = [];
//        $tabs = $this->container->getParameter('enhavo_media_library.tabs');
//        foreach($tabs as $tab) {
//            $data[] = [
//                'id' => $tab['id'],
//                'label' => $tab['label'],
//            ];
//        }
        return $data;
    }


    public function getType()
    {
        return 'media_library';
    }

    protected function createActions($options)
    {
        $default = [
            'add' => [
                'type' => 'event',
                'event' => 'add',
                'icon' => 'add_circle_outline',
                'label' => 'Hinzufügen'
            ]
        ];

        return $default;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'javascripts' => ['enhavo/media-library/media-library'],
            'stylesheets' => ['enhavo/media-library/media-library'],
            'items' => null,
            'tags' => [],
            'multiple' => true,
            'data' => [],
            'routes' => true
        ]);
    }
}
