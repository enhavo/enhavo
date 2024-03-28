<?php

namespace Enhavo\Bundle\MediaLibraryBundle\View\Type;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\Type\AppViewType;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class MediaLibraryViewType extends AbstractViewType
{
    const MODE_SELECT = 'select';
    const MODE_EDIT = 'edit';

    public function __construct(
        private ActionManager $actionManager,
        private ViewUtil $util,
        private TranslatorInterface $translator
    ) {}

    public static function getName(): ?string
    {
        return 'media_library';
    }

    public static function getParentType(): ?string
    {
        return AppViewType::class;
    }

    public function createViewData($options, ViewData $data)
    {
        $requestConfiguration = $this->util->getRequestConfiguration($options);

        $this->util->isGrantedOr403($requestConfiguration, ResourceActions::INDEX);

        $label = $this->util->mergeConfig([
            $options['label'],
            $this->util->getViewerOption('label', $requestConfiguration)
        ]);

        $actions = $this->util->mergeConfigArray([
            $this->createActions($options),
            $options['actions'],
            $this->util->getViewerOption('actions', $requestConfiguration)
        ]);

        $uploadRoute = $this->util->mergeConfig([
            $this->getUploadRoute($options),
            $options['upload_route'],
            $this->util->getViewerOption('upload_route', $requestConfiguration)
        ]);

        $data->set('items', $options['items']);
        $options['data']['contentTypes'] = $options['content_types'];
        $options['data']['multiple'] = $options['multiple'];
        $options['data']['mode'] = $options['mode'];
        $options['data']['tags'] = $options['tags'];
        $options['data']['limit'] = $options['limit'];
        $options['data']['uploadRoute'] = $uploadRoute;
        $data->set('data', $options['data']);
        $data->set('messages', []);
        $data->set('actions', $this->actionManager->createActionsViewData($actions));
        $data->set('label', $this->translator->trans($label, [], $options['translation_domain']));
        $data->set('modals', []);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'application' => '@enhavo/media-library/MediaLibraryApp',
            'component' => '@enhavo/media-library/components/MediaLibraryComponent.vue',
            'items' => null,
            'tags' => [],
            'limit' => null,
            'content_types' => [],
            'multiple' => true,
            'data' => [],
            'routes' => true,
            'mode' => self::MODE_EDIT,
            'request_configuration' => null,
            'request' => null,
            'metadata' => null,
            'actions' => [],
            'upload_route' => null
        ]);
    }

    private function createActions($options): array
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        if ($this->isModeEdit($options)) {
            $actions = [
                'upload' => [
                    'type' => 'event',
                    'event' => 'upload',
                    'icon' => 'cloud_upload',
                    'label' => 'media_library.label.upload',
                    'translation_domain' => 'EnhavoMediaLibraryBundle',
                    'permission' => $this->util->getRoleNameByResourceName($metadata->getApplicationName(), $this->util->getUnderscoreName($metadata), 'create'),
                ],
            ];

        } else if ($this->isModeSelect($options)) {
            $actions = [
                'add' => [
                    'type' => 'event',
                    'event' => 'add',
                    'icon' => 'check',
                    'translation_domain' => 'EnhavoMediaLibraryBundle',
                    'label' => 'media_library.label.confirm_selection',
                    'permission' => $this->util->getRoleNameByResourceName($metadata->getApplicationName(), $this->util->getUnderscoreName($metadata), 'update'),
                ],
                'upload' => [
                    'type' => 'event',
                    'event' => 'upload',
                    'icon' => 'cloud_upload',
                    'label' => 'media_library.label.upload',
                    'translation_domain' => 'EnhavoMediaLibraryBundle',
                    'permission' => $this->util->getRoleNameByResourceName($metadata->getApplicationName(), $this->util->getUnderscoreName($metadata), 'create'),
                ],
            ];
        }

        return $actions;
    }

    private function isModeEdit(array $options): bool
    {
        return $options['mode'] === self::MODE_EDIT;
    }

    private function isModeSelect(array $options): bool
    {
        return $options['mode'] === self::MODE_SELECT;
    }

    private function addFilterAction($actions)
    {
        if(!isset($actions['filter'])) {
            $actions['filter'] = [
                'type' => 'filter'
            ];
        }
        return $actions;
    }

    private function getUploadRoute($options): string
    {
        return 'enhavo_media_library_upload';
    }
}
