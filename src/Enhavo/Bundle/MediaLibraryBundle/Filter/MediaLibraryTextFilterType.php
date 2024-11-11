<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Filter;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Exception\FilterException;
use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;


class MediaLibraryTextFilterType extends AbstractFilterType
{
    public function buildQuery($options, FilterQuery $query, mixed $value): void
    {
        if ($value !== null && trim($value) !== '') {
            $parts = pathinfo($value);

            if (isset($parts['extension'])) {
                $query->addWhere('extension', FilterQuery::OPERATOR_LIKE, $parts['extension'], 'file');
                $query->addWhere('filename', FilterQuery::OPERATOR_LIKE, $parts['filename'], 'file');
            } else {
                $query->addWhere('filename', FilterQuery::OPERATOR_LIKE, $value, 'file');
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'operator' => FilterQuery::OPERATOR_LIKE,
            'component' => 'filter-text',
            'model' => 'TextFilter',
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library_text';
    }
}
