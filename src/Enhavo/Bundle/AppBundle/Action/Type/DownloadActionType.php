<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DownloadActionType extends AbstractActionType
{
    public function createViewData(array $options, Data $data, ResourceInterface $resource = null): void
    {
        $data->set('ajax', $options['ajax']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'label.download',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'file_download',
            'ajax' => false,
            'model' => 'DownloadAction',
        ]);
    }

    public static function getParentType(): ?string
    {
        return UrlActionType::class;
    }

    public static function getName(): ?string
    {
        return 'download';
    }
}
