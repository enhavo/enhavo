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
//        $requestConfiguration = $this->util->getRequestConfiguration($options);
//
//        $this->util->isGrantedOr403($requestConfiguration, ResourceActions::INDEX);
//
//        $label = $this->util->mergeConfig([
//            $options['label'],
//            $this->util->getViewerOption('label', $requestConfiguration)
//        ]);
//
//        $actions = $this->util->mergeConfigArray([
//            $this->createActions($options),
//            $options['actions'],
//            $this->util->getViewerOption('actions', $requestConfiguration)
//        ]);

        $data->set('items', $options['items']);
        $data->set('data', $options['data']);
        $options['data']['content_types'] = $options['content_types'];
        $options['data']['multiple'] = $options['multiple'];
        $options['data']['mode'] = $options['mode'];
        $options['data']['tags'] = $options['tags'];
        $data->set('data', $options['data']);
        $data->set('messages', []);
//        $data->set('actions', $this->actionManager->createActionsViewData($actions));
//        $data->set('label', $this->translator->trans($label, [], $options['translation_domain']));
        $data->set('modals', []);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'entrypoint' => 'enhavo/media-library/index',
            'items' => null,
            'tags' => [],
            'content_types' => [],
            'multiple' => true,
            'data' => [],
            'routes' => true,
            'mode' => self::MODE_EDIT,
        ]);
    }

    private function addTranslationDomain(&$configuration, $translationDomain)
    {
        foreach($configuration as &$config) {
            if(!isset($config['translation_domain']) && $translationDomain) {
                $config['translation_domain'] = $translationDomain;
            }
        }
    }

    private function getTableRoute($options): string
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];
        return sprintf('%s_%s_table', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata));
    }

    private function getBatchRoute($options): string
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];
        return sprintf('%s_%s_batch', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata));
    }

    private function getOpenRoute($options): string
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];
        return sprintf('%s_%s_update', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata));
    }

    private function createActions($options): array
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        $default = [
            'create' => [
                'type' => 'create',
                'route' => sprintf('%s_%s_create', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata)),
                'permission' => $this->util->getRoleNameByResourceName($metadata->getApplicationName(), $this->util->getUnderscoreName($metadata), 'create')
            ]
        ];


        if ($this->isModeEdit($options)) {
            $default = [
                'upload' => [
                    'type' => 'event',
                    'event' => 'upload',
                    'icon' => 'cloud_upload',
                    'label' => 'media_library.label.upload',
                    'translation_domain' => 'EnhavoMediaLibraryBundle',
                ],
            ];

        } else if ($this->isModeSelect($options)) {
            $default = [
                'add' => [
                    'type' => 'event',
                    'event' => 'add',
                    'icon' => 'check',
                    'translation_domain' => 'EnhavoMediaLibraryBundle',
                    'label' => 'media_library.label.confirm_selection'
                ]
            ];
        }
        return $default;
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
}
