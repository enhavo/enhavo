<?php
/**
 * MetadataCollection.php
 *
 * @since 05/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Metadata;


use Enhavo\Bundle\ArticleBundle\Entity\Article;

class MetadataCollection
{
    /**
     * @param $entity
     * @return Metadata|null
     */
    public function getMetadata($entity)
    {
        if($entity instanceof Article) {
            $metadata = new Metadata();
            $metadata->setClass('Enhavo\Bundle\ArticleBundle\Entity\Article');
            $teaser = new Property();
            $teaser->setName('teaser');
            $metaDescription = new Property();
            $metaDescription->setName('metaDescription');

            $metadata->setProperties([
                $teaser, $metaDescription
            ]);
            return $metadata;
        }
        return null;
    }
}