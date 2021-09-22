<?= $namespace; ?>\<?= $name; ?>Block:
    type: entity
    table: <?= $table_name; ?><?= "\n"; ?>

<?php if ($has_items): ?>
    oneToMany:
        items:
            cascade: [ 'persist', 'refresh' ]
            targetEntity: <?= $namespace; ?>\<?= $name; ?>BlockItem
            mappedBy: <?= strtolower($name); ?>Block
            orphanRemoval: true
            orderBy: { position: asc }
<?php endif; ?>

    lifecycleCallbacks: {  }
