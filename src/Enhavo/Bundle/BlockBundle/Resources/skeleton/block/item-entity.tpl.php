<?= "<?php\n" ?>

namespace <?= $class->getNamespace(); ?>;

<?php foreach ($class->getUse() as $item): ?>
use <?= $item; ?>;
<?php endforeach; ?>

class <?= $class->getName(); ?>

{

    /** @var ?int */
    private $id;

<?php foreach ($class->getProperties() as $property): ?>
    /** @var <?= $property->getNullable() .$property->getType() ?> */
    private $<?= $property->getName(); ?> = <?= $property->getDefault(); ?>;

<?php endforeach; ?>

    public function getId(): ?int
    {
        return $this->id;
    }

<?php foreach ($class->getFunctions() as $function): ?>

    <?= $function->getVisibility(); ?> function <?= $function->getName(); ?>(<?= $function->getArgumentString(); ?>)<?= $function->getReturnsString(); ?>

    {
        <?= $function->getBodyString(8); ?>

    }
<?php endforeach; ?>
}
