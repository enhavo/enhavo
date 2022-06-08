<?= "<?php\n" ?>

namespace <?= $class->getNamespace(); ?>;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
<?php foreach ($class->getUse() as $item) { ?>
use <?= $item; ?>;
<?php } ?>
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: '<?= $orm->getTableName() ?>')]
class <?= $class->getName(); ?> extends AbstractBlock
{
<?php foreach ($class->getProperties() as $property) { ?>
<?php $attributeType = $orm->getAttributeType($property->getName()); ?>
    #[ORM\<?= $attributeType ?>(
<?php foreach ($orm->getAttributeOptions($property->getName()) as $key => $value) { ?>
        <?= $key ?>: <?= $value ?>,
<?php } ?>
<?php $relation = $orm->getRelation($property->getName()); ?>
<?php if ($attributeType === 'OneToOne') { ?>
        targetEntity: <?= $relation->getTargetEntity() ?>,
        cascade: [ 'persist', 'refresh', 'remove' ],
<?php } else if ($attributeType === 'OneToMany') { ?>
        mappedBy: '<?= $relation->getMappedBy() ?>',
        targetEntity: <?= $relation->getTargetEntity() ?>,
        cascade: [ 'persist', 'refresh', 'remove' ],
        orphanRemoval: true,
<?php } else if ($attributeType === 'ManyToOne') { ?>
        targetEntity: <?= $relation->getTargetEntity() ?>,
        inversedBy: '<?= $relation->getInversedBy() ?>',
<?php } else if ($attributeType === 'ManyToMany') { ?>
        targetEntity: <?= $relation->getTargetEntity() ?>,
        cascade: ['persist', 'refresh', 'remove'],
<?php } ?>
    )]
<?php if ($attributeType === 'ManyToOne') { ?>
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
<?php } else if ($attributeType === 'ManyToMany') { ?>
    #[ORM\JoinTable(name: '<?= $orm->getTableName() ?>_<?= $relation->getName() ?>')]
    #[ORM\JoinColumn(
        name: 'entity_id',
        referencedColumnName: 'id',
        onDelete: 'cascade',
    )]
    #[ORM\InverseJoinColumn(
        name: 'target_id',
        referencedColumnName: 'id',
        onDelete: 'cascade',
    )]
<?php } ?>
<?php if ($relation) { ?>
    #[ORM\OrderBy(<?= $relation->getOrderByString() ?>)]
<?php } ?>
    private <?= $property->getNullable() .$property->getType() ; ?> $<?= $property->getName(); ?><?php if ($property->getDefault() !== 'null'): ?> = <?= $property->getDefault(); ?><?php endif; ?>;

<?php } ?>
<?php foreach ($class->getFunctions() as $function) { ?>
    <?= $function->getVisibility(); ?> function <?= $function->getName(); ?>(<?= $function->getArgumentString(); ?>)<?= $function->getReturnsString(); ?>

    {
        <?= $function->getBodyString(8); ?>

    }
<?php } ?>
}
