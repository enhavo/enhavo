<?php
/**
 * GridTableStructureMigration.php
 *
 * @since 26/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\MigrationBundle\Migration\M0M1H0;

use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\GridBundle\Entity\Item;
use esperanto\ContentBundle\Entity\Configuration;
use esperanto\ContentBundle\Item\Type\Picture;
use esperanto\ContentBundle\Item\Type\PicturePicture;
use esperanto\ContentBundle\Item\Type\Text;
use esperanto\ContentBundle\Item\Type\TextPicture;
use Symfony\Component\Config\Definition\Exception\Exception;
use Enhavo\Bundle\MigrationBundle\Migration\AbstractMigration;

class GridTableStructureMigration extends AbstractMigration
{
    public function migrate()
    {
        $items = array();
        try {
            $items = $this->getDatabaseModifier()->executeQuery('SELECT * FROM migrate_content_item');
        } catch(\Exception $e ) {

        }

        foreach($items as $item) {
            $this->migrateItem($item);
        }

        return;
    }

    protected function migrateItem($item)
    {
        if(!isset($item['configuration'])) {
            return;
        }

        $configuration = unserialize($item['configuration']);

        if(empty($item['content_id'])) {
            return;
        }

        if(!$configuration instanceof Configuration) {
            throw new Exception('can\'t convert to Configuration');
        }

        switch($configuration->getType()) {
            case('text'):
                $itemType = $this->migrateText($item, $configuration->getData());
                break;
            case('picturepicture'):
                $itemType = $this->migratePicturePicture($item, $configuration->getData());
                break;
            case('picture'):
                $itemType = $this->migratePicture($item, $configuration->getData());
                break;
            case('textpicture'):
                $itemType = $this->migrateTextPicture($item, $configuration->getData());
                break;
            case('video'):
                $itemType = $this->migrateVideo($item, $configuration->getData());
                break;
            default:
                throw new Exception(sprintf('unknown type [%s]', $configuration->getType()));
        }

        $grid = $this->getGrid($item);
        $gridItem = new Item();
        $gridItem->setGrid($grid);
        $gridItem->setOrder($item['order']);
        $gridItem->setItemType($itemType);

        $this->getManager()->persist($gridItem);
        $this->getManager()->flush();
    }

    protected function getGrid($item)
    {
        return $this->getManager()->getRepository('EnhavoGridBundle:Grid')->find($item['content_id']);
    }

    protected function getSingleFile($filesCollection)
    {
        //echo serialize($filesCollection);

        $data = serialize($filesCollection);

        $data = unserialize($data);
        $data = var_export($data, true);
        $data = preg_replace('#[A-Za-z\\\]*::__set_state#', '', $data);
        $data = eval('return '.$data.';');
        $filesCollection = new ArrayCollection($data['_elements']);

        if($filesCollection && count($filesCollection) == 1) {
            return $this->getFile($filesCollection[0]);
        }

        if($filesCollection && count($filesCollection) > 1) {
            throw new Exception(sprintf('more than one file'));
        }
        
        return null;
    }

    protected function getFile($fileData)
    {
        return $this->getManager()->getRepository('EnhavoMediaBundle:File')->find($fileData['id']);
    }

    protected function migrateText($item, $itemType)
    {
        $text = new \Enhavo\Bundle\GridBundle\Entity\Text();
        /** @var $itemType Text */
        $text->setText($itemType->getText());
        $text->setTitle($itemType->getTitle());
        return $text;
    }

    protected function migratePicturePicture($item, $itemType)
    {
        $picturePicture = new \Enhavo\Bundle\GridBundle\Entity\PicturePicture();
        /** @var $itemType PicturePicture */
        $picturePicture->setFileLeft($this->getSingleFile($itemType->getFilesLeft()));
        $picturePicture->setFileRight($this->getSingleFile($itemType->getFilesRight()));
        return $picturePicture;
    }

    protected function migratePicture($item, $itemType)
    {
        $picture = new \Enhavo\Bundle\GridBundle\Entity\Picture();
        /** @var $itemType Picture */
        $picture->setFile($this->getSingleFile($itemType->getFiles()));
        $picture->setTitle($itemType->getTitle());
        return $picture;
    }

    protected function migrateTextPicture($item, $itemType)
    {
        $textPicture = new \Enhavo\Bundle\GridBundle\Entity\TextPicture();
        /** @var $itemType TextPicture */
        $textPicture->setText($itemType->getText());
        $textPicture->setFile($this->getSingleFile($itemType->getFiles()));
        $textPicture->setTitle($itemType->getTitle());
        $textPicture->setTextLeft($itemType->getTextleft());
        return $textPicture;
    }

    protected function migrateVideo($item, $itemType)
    {
        $video = new \Enhavo\Bundle\GridBundle\Entity\Video();
        return $video;
    }
}