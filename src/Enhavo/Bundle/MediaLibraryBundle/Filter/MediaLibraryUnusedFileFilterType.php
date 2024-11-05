<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Filter;

use Enhavo\Bundle\ResourceBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibraryUnusedFileFilterType extends AbstractFilterType
{
    public function buildQuery($options, FilterQuery $query, mixed $value): void
    {
        if ($value) {
            $qb = $query->getQueryBuilder();
            $alias = $qb->getRootAliases()[0];
            $qb->leftJoin($alias.'.usedFiles', 'uf');
            $qb->andWhere('uf.id IS NULL');

            // ToDo: Used files that are garbage 0 also count to unused. So we need to update the query. Here is SQL how it might work
            // but at this point we are unable to use it with the query builder
            // SELECT mli1.*, JO1.number FROM media_library_item mli1 LEFT JOIN (SELECT mli.id AS id, COUNT(*) AS number FROM media_library_item mli LEFT JOIN media_file ON media_file.item_id = mli.id  WHERE media_file.garbage = 1 GROUP BY mli.id) JO1 ON JO1.id = mli1.id GROUP BY mli1.id HAVING JO1.number IS NULL
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'filter-media-library-checkbox',
            'model' => 'BooleanFilter',
            'label' => 'media_library.filter.label.unused',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
            'initial_value' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library_unused_file';
    }
}
