<?= "<?php\n" ?>

namespace <?= $definition->getEntityNamespace() ?>;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
<?php foreach ($class->getUse() as $item): ?>
use <?= $item; ?>;
<?php endforeach; ?>

class <?= $definition->getName() ?> extends AbstractBlock
{
<?php foreach ($class->getProperties() as $property): ?>
<?= $property ?>
<?php endforeach; ?>

<?php foreach ($class->getFunctions() as $function): ?>
<?= $function ?>
<?php endforeach; ?>
}
