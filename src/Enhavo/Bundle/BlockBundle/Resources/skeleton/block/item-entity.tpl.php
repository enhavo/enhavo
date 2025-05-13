<?php echo "<?php\n"; ?>

namespace <?php echo $class->getNamespace(); ?>;


<?php foreach ($class->getUse() as $item) { ?>
use <?php echo $item; ?>;
<?php } ?>
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Enhavo\Bundle\ResourceBundle\Attribute\Duplicate;

#[ORM\Entity]
#[ORM\Table(name: '<?php echo $orm->getTableName(); ?>')]
class <?php echo $class->getName(); ?><?php if ($class->getImplements()) { ?> implements <?php echo $class->getImplements(); ?><?php } ?>

{
<?php foreach ($class->getTraits() as $value) { ?>
    use <?php echo $value; ?>;
<?php } ?>

<?php foreach ($class->getProperties() as $property) { ?>
<?php $ormField = $orm->getField($property->getName()); ?>
<?php if ($ormField->isPrimaryKey()) { ?>
    #[ORM\Id]
<?php } ?>
<?php if ($ormField->getGeneratedValueType()) { ?>
    #[ORM\GeneratedValue(strategy: '<?php echo $ormField->getGeneratedValueType(); ?>')]
<?php } ?>
<?php $attributeType = $orm->getAttributeType($property->getName()); ?>
    #[ORM\<?php echo $attributeType; ?>(
<?php foreach ($orm->getAttributeOptions($property->getName()) as $key => $value) { ?>
        <?php echo $key; ?>: <?php echo $value; ?>,
<?php } ?>
<?php $relation = $orm->getRelation($property->getName()); ?>
<?php if ('OneToOne' === $attributeType) { ?>
        <?php if ($relation->getTargetEntity()) { ?>targetEntity: <?php echo $relation->getTargetEntity(); ?>,
<?php } ?>
        cascade: [ 'persist', 'refresh', 'remove' ],
<?php } elseif ('OneToMany' === $attributeType) { ?>
        <?php if ($relation->getMappedBy()) { ?>mappedBy: '<?php echo $relation->getMappedBy(); ?>',
<?php } ?>
        <?php if ($relation->getTargetEntity()) { ?>targetEntity: <?php echo $relation->getTargetEntity(); ?>,
<?php } ?>
        cascade: [ 'persist', 'refresh', 'remove' ],
        orphanRemoval: true,
<?php } elseif ('ManyToOne' === $attributeType) { ?>
        <?php if ($relation->getTargetEntity()) { ?>targetEntity: <?php echo $relation->getTargetEntity(); ?>,
<?php } ?>
        <?php if ($relation->getInversedBy()) { ?>inversedBy: '<?php echo $relation->getInversedBy(); ?>',
<?php } ?>
<?php } elseif ('ManyToMany' === $attributeType) { ?>
        <?php if ($relation->getTargetEntity()) { ?>targetEntity: <?php echo $relation->getTargetEntity(); ?>,
<?php } ?>
        cascade: ['persist', 'refresh', 'remove'],
<?php } ?>
    )]
<?php if ('ManyToOne' === $attributeType) { ?>
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
<?php } elseif ('ManyToMany' === $attributeType) { ?>
    #[ORM\JoinTable(name: '<?php echo $orm->getTableName(); ?>_<?php echo $relation->getName(); ?>')]
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
    #[ORM\OrderBy(<?php echo $relation->getOrderByString(); ?>)]
<?php } ?>
<?php if ($property->hasSerializationGroups()) { ?>
    #[Groups(<?php echo $property->getSerializationGroupsString(); ?>)]
<?php } ?>
<?php foreach ($property->getAttributes() as $rule) { ?>
    #[<?php echo $rule['class']; ?>(<?php echo $rule['type'] ? ("'".$rule['type']."'").(isset($rule['options']) ? ', ' : '') : ''; ?><?php echo $rule['options'] ?? ''; ?>)]
<?php } ?>
<?php if ($property->getNullable() || 'null' !== $property->getDefault()) { ?>
    private <?php echo $property->getNullable().$property->getType(); ?> $<?php echo $property->getName(); ?> = <?php echo $property->getDefault(); ?>;
<?php } else { ?>
    private <?php echo $property->getNullable().$property->getType(); ?> $<?php echo $property->getName(); ?>;
<?php } ?>

<?php } ?>
<?php foreach ($class->getFunctions() as $function) { ?>

    <?php echo $function->getVisibility(); ?> function <?php echo $function->getName(); ?>(<?php echo $function->getArgumentString(); ?>)<?php echo $function->getReturnsString(); ?>

    {
        <?php echo $function->getBodyString(8); ?>

    }
<?php } ?>
}
