<?= $namespace; ?>\<?= $name; ?>BlockItem:
    type: entity
    table: <?= $table_name; ?><?= "\n"; ?>

    id:
        id:
            type: integer
            id: true
            generator:
                strategy: AUTO

    fields:
        position:
            type: integer
            nullable: true

    manyToOne:
        <?= strtolower($name); ?>Block:
            targetEntity: <?= $namespace; ?>\<?= $name; ?>Block
            cascade: [ 'persist', 'refresh', 'remove' ]
            inversedBy: items
            joinColumn:
                onDelete: CASCADE

    lifecycleCallbacks: {  }
