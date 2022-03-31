<?= $bundle_name; ?>_<?= $name; ?>_index:
    options:
        expose: true
    path: /<?= $bundle_url; ?>/<?= $name_url; ?>/index
    methods: [GET]
    defaults:
        _controller: enhavo_taxonomy.controller.term:<?php if($hierarchy): ?>list<?php else: ?>index<?php endif; ?>Action
        _sylius:
            viewer:
                title: taxonomy.label.term
                translationDomain: EnhavoTaxonomyBundle
                <?php if($hierarchy): ?>data<?php else: ?>table<?php endif; ?>_route: <?= $bundle_name; ?>_<?= $name; ?>_<?php if($hierarchy): ?>data<?php else: ?>table<?php endif; ?>
                batch_route: <?= $bundle_name; ?>_<?= $name; ?>_batch
                open_route: <?= $bundle_name; ?>_<?= $name; ?>_update
                actions:
                    create:
                        type: create
                        route: <?= $bundle_name; ?>_<?= $name; ?>_create

<?= $bundle_name; ?>_<?= $name; ?>_create:
    options:
        expose: true
    path:  /<?= $bundle_url; ?>/<?= $name_url; ?>/create
    methods: [GET,POST]
    defaults:
        _controller: enhavo_taxonomy.controller.term:createAction
        _sylius:
            redirect: <?= $bundle_name; ?>_<?= $name; ?>_update
            form: '<?= $form_type; ?>'
            factory:
                method: createNewOnTaxonomy
                arguments:
                    - '<?= $name; ?>'
            viewer:
                translationDomain: EnhavoTaxonomyBundle

<?= $bundle_name; ?>_<?= $name; ?>_update:
    options:
        expose: true
    path:  /<?= $bundle_url; ?>/<?= $name_url; ?>/update/{id}
    methods: [GET,POST]
    defaults:
        _controller: enhavo_taxonomy.controller.term:updateAction
        _sylius:
            form: '<?= $form_type; ?>'
            viewer:
                translationDomain: EnhavoTaxonomyBundle
                actions_secondary:
                    delete:
                        type: delete
                        route: <?= $bundle_name; ?>_<?= $name; ?>_delete

<?php if(!$hierarchy): ?>
<?= $bundle_name; ?>_<?= $name; ?>_table:
    options:
        expose: true
    path:  /<?= $bundle_url; ?>/<?= $name_url; ?>/table
    defaults:
        _controller: enhavo_taxonomy.controller.term:tableAction
        _sylius:
            repository:
                method: findByTaxonomyName
                arguments:
                    - '<?= $name; ?>'
            viewer:
                translationDomain: EnhavoTaxonomyBundle
                width: 12
                columns:
                    name:
                        type: property
                        width: 12
                        label: term.label.name
                        translation_domain: EnhavoTaxonomyBundle
                        property: name
<?php else: ?>
<?= $bundle_name; ?>_<?= $name; ?>_data:
    options:
        expose: true
    path:  /<?= $bundle_url; ?>/<?= $name_url; ?>/data
    defaults:
        _controller: enhavo_taxonomy.controller.term:listDataAction
        _sylius:
            repository:
                method: findRootsByTaxonomyName
                arguments:
                    - '<?= $name; ?>'
            viewer:
                translationDomain: EnhavoTaxonomyBundle
                width: 12
                children_property: children
                parent_property: parent
                columns:
                    name:
                        type: property
                        width: 12
                        label: term.label.name
                        translation_domain: EnhavoTaxonomyBundle
                        property: name
<?php endif; ?>

<?= $bundle_name; ?>_<?= $name; ?>_delete:
    options:
        expose: true
    path: /<?= $bundle_url; ?>/<?= $name_url; ?>/delete/{id}
    methods: [POST]
    defaults:
        _controller: enhavo_taxonomy.controller.term:deleteAction

<?= $bundle_name; ?>_<?= $name; ?>_batch:
    options:
        expose: true
    path: /<?= $bundle_url; ?>/<?= $name_url; ?>/batch
    methods: [POST]
    defaults:
        _controller: enhavo_taxonomy.controller.term:batchAction
        _sylius:
            paginate: false
            criteria:
                id: $ids
            batches:
                delete:
                    type: delete
