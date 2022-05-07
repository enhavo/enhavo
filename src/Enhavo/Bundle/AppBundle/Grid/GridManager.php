<?php

namespace Enhavo\Bundle\AppBundle\Grid;

use Symfony\Contracts\Translation\TranslatorInterface;

class GridManager
{
    public function __construct(
        private TranslatorInterface $translator
    ) {}

    public function createTabViewData(array $tabs, ?string $translationDomain = null): array
    {
        $data = [];
        foreach ($tabs as $key => $tab) {
            $tabData = [];
            $tabData['label'] = $this->translator->trans($tab['label'], [], isset($tab['translation_domain']) ? $tab['translation_domain'] : $translationDomain);
            $tabData['key'] = $key;
            $tabData['fullWidth'] = isset($tab['full_width']) && $tab['full_width'] ? true : false;
            $tabData['template'] = $tab['template'];
            $data[] = $tabData;
        }
        return $data;
    }
}
