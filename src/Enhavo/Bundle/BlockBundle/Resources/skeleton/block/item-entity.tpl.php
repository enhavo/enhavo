<?= "<?php\n" ?>

namespace <?= $class->getNamespace(); ?>;

<?php foreach ($class->getUse() as $item): ?>
use <?= $item; ?>;
<?php endforeach; ?>

class <?= $class->getName(); ?>
{

<?php foreach ($class->getProperties() as $property): ?>
<?= $property; ?>
<?php endforeach; ?>

<?php foreach ($class->getFunctions() as $function): ?>
<?= $function; ?>
<?php endforeach; ?>
}
