<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\AbstractActionViewer;
use FOS\RestBundle\View\View;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibraryViewer extends AbstractActionViewer
{
    const MODE_SELECT = 'select';
    const MODE_EDIT = 'edit';

    /**
     * {@inheritdoc}
     */
    public function createView($options = []): View
    {
        $view = parent::createView($options);

        $templateVars = $view->getTemplateData();
        $templateVars['data']['items'] = $options['items'];
        $templateVars['data']['data'] = $options['data'];
        $templateVars['data']['data']['content_types'] = $options['content_types'];
        $templateVars['data']['data']['multiple'] = $options['multiple'];
        $templateVars['data']['data']['mode'] = $options['mode'];
        $templateVars['data']['data']['tags'] = $options['tags'];
        $templateVars['data']['messages'] = [];

        $view->setTemplateData($templateVars);

        return $view;
    }

    public function getType()
    {
        return 'media_library';
    }

    protected function createActions($options)
    {
        if ($this->isModeEdit($options)) {
            $default = [
                'add' => [
                    'type' => 'create',
                    'route' => 'enhavo_media_library_create',
                    'icon' => 'add_circle_outline',
                    'translation_domain' => 'EnhavoMediaLibraryBundle',
                    'label' => 'media.library.create'
                ]
            ];

        } else if ($this->isModeSelect($options)) {
            $default = [
                'add' => [
                    'type' => 'event',
                    'event' => 'add',
                    'icon' => 'check',
                    'translation_domain' => 'EnhavoMediaLibraryBundle',
                    'label' => 'media.library.confirm_selection'
                ]
            ];
        }

        return $default;
    }

    protected function isModeEdit(array $options): bool
    {
        return $options['mode'] === self::MODE_EDIT;
    }

    protected function isModeSelect(array $options): bool
    {
        return $options['mode'] === self::MODE_SELECT;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'javascripts' => ['enhavo/media-library/media-library'],
            'stylesheets' => ['enhavo/media-library/media-library'],
            'items' => null,
            'tags' => [],
            'content_types' => [],
            'multiple' => true,
            'data' => [],
            'routes' => true,
            'mode' => self::MODE_EDIT,
        ]);
    }
}
