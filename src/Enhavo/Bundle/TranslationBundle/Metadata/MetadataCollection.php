<?php
/**
 * MetadataCollection.php
 *
 * @since 05/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Metadata;


use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Bundle\GridBundle\Entity\Text;

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

            $title = new Property();
            $title->setName('title');

            $metadata->setProperties([
                $teaser, $metaDescription, $title
            ]);
            return $metadata;
        }


        if($entity instanceof Text) {
            $metadata = new Metadata();
            $metadata->setClass('Enhavo\Bundle\GridBundle\Entity\Text');

            $text = new Property();
            $text->setName('text');

            $metadata->setProperties([
                $text
            ]);
            return $metadata;
        }
        return null;
    }
}