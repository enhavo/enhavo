<?php
/**
 * Page.php
 *
 * @since 04/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ProjectBundle\Entity;

use esperanto\PageBundle\Entity\Page as BasePage;
use esperanto\SearchBundle\Search\SearchIndexInterface;
use esperanto\ContentBundle\Entity\Item;

class Page extends BasePage implements SearchIndexInterface
{


    public function getIndexTitle()
    {
        return $this->getTitle();
    }

    public function getIndexTeaser()
    {
        return $this->getTeaser();
    }

    public function getIndexContent()
    {
        $content = array();
        if($this->getContent() && $this->getContent()->getItems()) {
            /** @var $item Item */
            foreach($this->getContent()->getItems() as $item) {
                if($item->getConfiguration()->getType() == 'text') {
                    $content[] = $this->getTitle();
                    $content[] = $this->getTeaser();
                    $content[] = html_entity_decode(strip_tags($item->getConfiguration()->getData()->getText()));
                    $content[] = $item->getConfiguration()->getData()->getTitle();
                }
            }
        }
        return implode("\n", $content);
    }

    public function getIndexRoute()
    {
        return 'esperanto_project_index';
    }

    public function getIndexRouteParameter()
    {
        return array(
            'id' => $this->getId(),
            'slug' => $this->getSlug()
        );
    }


}