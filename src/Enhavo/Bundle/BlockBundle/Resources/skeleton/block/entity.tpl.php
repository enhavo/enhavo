<?= "<?php\n" ?>

namespace <?= $class->getNamespace(); ?>;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
<?php foreach ($class->getUse() as $item): ?>
use <?= $item; ?>;
<?php endforeach; ?>

class <?= $class->getName(); ?> extends AbstractBlock
{

<?php foreach ($class->getProperties() as $property): ?>
<?= $property; ?>
<?php endforeach; ?>

<?php foreach ($class->getFunctions() as $function): ?>
<?= $function; ?>
<?php endforeach; ?>
}
