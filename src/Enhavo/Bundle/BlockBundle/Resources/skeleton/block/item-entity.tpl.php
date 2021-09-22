<?= "<?php\n" ?>

namespace <?= $entity_namespace ?>;

class <?= $name ?>BlockItem
{
    /** @var int|null */
    private $id;

    /** @var <?= $name ?>Block|null */
    private $<?= strtolower($name) ?>Block;

    /** @var int|null */
    private $position;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return <?= $name ?>Block|null
     */
    public function get<?= $name ?>Block(): ?<?= $name ?>Block
    {
        return $this-><?= strtolower($name) ?>Block;
    }

    /**
     * @param <?= $name ?>Block|null $<?= strtolower($name) ?>Block
     */
    public function set<?= $name ?>Block(?<?= $name ?>Block $<?= strtolower($name) ?>Block): void
    {
        $this-><?= strtolower($name) ?>Block = $<?= strtolower($name) ?>Block;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int|null $position
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }
}
