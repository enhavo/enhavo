<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Filter;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Exception\FilterException;
use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;


class MediaLibraryContentTypeFilterType extends AbstractFilterType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly array $contentTypes,
    )
    {
    }

    public function createViewData($options, Data $data): void
    {
        $choices = $this->formatChoices($options);
        $this->validateInitialValue($options['initial_value'], $choices);

        $data->add([
            'choices' => $choices
        ]);
    }

    private function formatChoices($options): array
    {
        $data = [];
        foreach ($this->contentTypes as $key => $contentType) {
            $data[] = [
                'label' => $this->translator->trans($contentType['label'], [], $contentType['translation_domain'] ?? null),
                'icon' => $contentType['icon'],
                'code' => $key,
            ];
        }
        return $data;
    }


    private function validateInitialValue($value, $choices): void
    {
        if ($value === null) {
            return;
        }
        foreach ($choices as $choice) {
            if ($choice['code'] == $value) {
                return;
            }
        }

        throw new \InvalidArgumentException('Parameter "initial_value" must either be null or one of keys in parameter "options"');
    }

    public function buildQuery($options, FilterQuery $query, mixed $value): void
    {
        if ($value === null || trim($value) === '') {
            return;
        }

        $possibleValues = array_keys($this->contentTypes);
        $findPossibleValue = false;
        foreach ($possibleValues as $possibleValue) {
            if ($possibleValue == $value) {
                $findPossibleValue = true;
                break;
            }
        }

        if (!$findPossibleValue) {
            throw new FilterException('Value does not exists in options');
        }

        $query->addWhere('contentType', FilterQuery::OPERATOR_EQUALS, $value);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'filter-media-library-choice',
            'model' => 'OptionFilter',
            'initial_value' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library_content_type';
    }
}
