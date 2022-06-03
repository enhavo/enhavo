<?= "<?php\n" ?>

namespace <?= $class->getNamespace(); ?>;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
<?php foreach ($class->getUse() as $item): ?>
use <?= $item; ?>;
<?php endforeach; ?>
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '<?= $orm->getTableName() ?>')]
class <?= $class->getName(); ?> extends AbstractBlock
{
<?php foreach ($class->getProperties() as $property): ?>
<?php $attributeType = $orm->getAttributeType($property->getName()); ?>
    #[Orm\<?= $attributeType ?>(
    <?php foreach ($orm->getAttributeOptions($property->getName()) as $key => $value): ?>
        <?= $key ?>: <?= $value ?>,
    <?php endforeach; ?>
<?php $relation = $orm->getRelation($property->getName()); ?>
    <?php if ($attributeType === 'OneToOne'): ?>
        targetEntity: <?= $relation->getTargetEntity() ?>
        cascade: [ 'persist', 'refresh', 'remove' ],
    <?php elseif ($attributeType === 'OneToMany'): ?>
        cascade: [ 'persist', 'refresh', 'remove' ]
        mappedBy: <?= $relation->getMappedBy() ?>
        orderBy: <?= $relation->getOrderByString() ?>
        orphanRemoval: true
        targetEntity: <?= $relation->getTargetEntity() ?>
    <?php elseif ($attributeType === 'ManyToOne'): ?>
        targetEntity: <?= $relation->getTargetEntity() ?>
        inversedBy: <?= $relation->getInversedBy() ?>
        joinColumn: ['onDelete' => 'SET NULL']

    <?php endif; ?>
)]
    private <?= $property->getNullable() .$property->getType() ; ?> $<?= $property->getName(); ?><?php if ($property->getDefault() !== 'null'): ?> = <?= $property->getDefault(); ?><?php endif; ?>;

<?php endforeach; ?>
<?php foreach ($class->getFunctions() as $function): ?>

    <?= $function->getVisibility(); ?> function <?= $function->getName(); ?>(<?= $function->getArgumentString(); ?>)<?= $function->getReturnsString(); ?>

    {
        <?= $function->getBodyString(8); ?>

    }
<?php endforeach; ?>
}
