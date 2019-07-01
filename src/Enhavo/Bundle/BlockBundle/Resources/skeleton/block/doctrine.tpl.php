<?= $bundle_namespace; ?>\Entity\<?=  $block_sub_directory ? $block_sub_directory.'\\' : ''; ?><?= $block_name; ?>:
    type: entity
    table: <?= $table_name; ?><?= "\n"; ?>

    lifecycleCallbacks: {  }
