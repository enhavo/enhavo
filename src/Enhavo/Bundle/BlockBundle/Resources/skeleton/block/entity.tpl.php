<?= "<?php\n" ?>

namespace <?= $class->getNamespace(); ?>;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
<<<<<<< HEAD
=======
<?php foreach ($class->getUse() as $item): ?>
use <?= $item; ?>;
<?php endforeach; ?>
>>>>>>> 941963ef3 (Block maker by yaml (#1355))

class <?= $class->getName(); ?> extends AbstractBlock
{

<<<<<<< HEAD
=======
<?php foreach ($class->getProperties() as $property): ?>
    /** @var <?= $property->getNullable() .$property->getType() ?> */
    private $<?= $property->getName(); ?> = <?= $property->getDefault(); ?>;

<?php endforeach; ?>

<?php foreach ($class->getFunctions() as $function): ?>

    <?= $function->getVisibility(); ?> function <?= $function->getName(); ?>(<?= $function->getArgumentString(); ?>)<?= $function->getReturnsString(); ?>

    {
        <?= $function->getBodyString(8); ?>

    }
<?php endforeach; ?>
>>>>>>> 941963ef3 (Block maker by yaml (#1355))
}
