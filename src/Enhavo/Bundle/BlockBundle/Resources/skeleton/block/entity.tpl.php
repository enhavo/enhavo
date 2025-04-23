<?= "<?php\n" ?>

namespace <?= $class->getNamespace(); ?>;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
<?php foreach ($class->getUse() as $item) { ?>
use <?= $item; ?>;
<?php } ?>
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: '<?= $orm->getTableName() ?>')]
class <?= $class->getName(); ?> extends AbstractBlock<?php if ($class->getImplements()): ?> implements <?= $class->getImplements(); ?><?php endif; ?>

{
<?php foreach ($class->getTraits() as $value) { ?>
    use <?= $value ?>;
<?php } ?>

<?php foreach ($class->getProperties() as $property) { ?>
<?php $attributeType = $orm->getAttributeType($property->getName()); ?>
    #[ORM\<?= $attributeType ?>(
<?php foreach ($orm->getAttributeOptions($property->getName()) as $key => $value) { ?>
        <?= $key ?>: <?= $value ?>,
<?php } ?>
<?php $relation = $orm->getRelation($property->getName()); ?>
<?php if ($attributeType === 'OneToOne') { ?>
        <?php if ($relation->getTargetEntity()) { ?>targetEntity: <?= $relation->getTargetEntity() ?>,
<?php } ?>
        cascade: [ 'persist', 'refresh', 'remove' ],
<?php } else if ($attributeType === 'OneToMany') { ?>
        <?php if ($relation->getMappedBy()) { ?>mappedBy: '<?= $relation->getMappedBy() ?>',
<?php } ?>
        <?php if ($relation->getTargetEntity()) { ?>targetEntity: <?= $relation->getTargetEntity() ?>,
<?php } ?>
        cascade: [ 'persist', 'refresh', 'remove' ],
        orphanRemoval: true,
<?php } else if ($attributeType === 'ManyToOne') { ?>
        <?php if ($relation->getTargetEntity()) { ?>targetEntity: <?= $relation->getTargetEntity() ?>,
<?php } ?>
        <?php if ($relation->getInversedBy()) { ?>inversedBy: '<?= $relation->getInversedBy() ?>',
<?php } ?>
<?php } else if ($attributeType === 'ManyToMany') { ?>
        <?php if ($relation->getTargetEntity()) { ?>targetEntity: <?= $relation->getTargetEntity() ?>,
<?php } ?>
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
<?php if ($relation && $relation->getOrderBy()) { ?>
    #[ORM\OrderBy(<?= $relation->getOrderByString() ?>)]
<?php } ?>
<?php if ($property->hasSerializationGroups()) { ?>
    #[Groups([<?= $property->getSerializationGroupsString(); ?>])]
<?php } ?>
<?php if ($property->getNullable() || $property->getDefault() !== 'null') { ?>
    private <?= $property->getNullable() .$property->getType() ; ?> $<?= $property->getName(); ?> = <?= $property->getDefault(); ?>;
<?php } else { ?>
    private <?= $property->getNullable() .$property->getType() ; ?> $<?= $property->getName(); ?>;
<?php } ?>

<?php } ?>
<?php foreach ($class->getFunctions() as $function) { ?>

    <?= $function->getVisibility(); ?> function <?= $function->getName(); ?>(<?= $function->getArgumentString(); ?>)<?= $function->getReturnsString(); ?>

    {
        <?= $function->getBodyString(8); ?>

    }
<?php } ?>
}
