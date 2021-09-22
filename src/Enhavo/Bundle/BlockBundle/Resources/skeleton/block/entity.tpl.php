<?= "<?php\n" ?>

namespace <?= $entity_namespace ?>;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
<?php if ($has_items): ?>
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
<?php endif; ?>

class <?= $name ?>Block extends AbstractBlock
{
<?php if ($has_items): ?>
    /** @var Collection<<?= $name ?>BlockItem> */
    private $items;

    /**
    * LogowallBlock constructor.
    */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
    * @return Collection
    */
    public function getItems()
    {
        return $this->items;
    }

    /**
    * @param <?= $name ?>BlockItem $item
    */
    public function addItem(<?= $name ?>BlockItem $item)
    {
        $this->items->add($item);
        $item->set<?= $name ?>Block($this);
    }

    /**
    * @param <?= $name ?>BlockItem $item
    */
    public function removeItem(<?= $name ?>BlockItem $item)
    {
        $this->items->removeElement($item);
        $item->set<?= $name ?>Block(null);
    }

<?php endif; ?>
}
