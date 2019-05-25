<?= $bundle_name; ?>_<?= $name; ?>_index:
    options:
        expose: true
    path: /<?= $bundle_url; ?>/<?= $name_url; ?>/index
    methods: [GET]
    defaults:
        _controller: enhavo_taxonomy.controller.taxonomy:indexAction
        _sylius:
            viewer:
                title: taxonomy.label.term
                translationDomain: EnhavoTaxonomyBundle

<?= $bundle_name; ?>_<?= $name; ?>_create:
    options:
        expose: true
    path:  /<?= $bundle_url; ?>/<?= $name_url; ?>/create
    methods: [GET,POST]
    defaults:
        _controller: enhavo_taxonomy.controller.taxonomy:createAction
        _sylius:
            redirect: <?= $bundle_name; ?>_<?= $name; ?>_update
            viewer:
                translationDomain: EnhavoTaxonomyBundle
                tabs:
                    main:
                        label: taxonomy.label.taxonomy
                        template: EnhavoTaxonomyBundle:Tab:taxonomy.html.twig

<?= $bundle_name; ?>_<?= $name; ?>_update:
    options:
        expose: true
    path:  /<?= $bundle_url; ?>/<?= $name_url; ?>/update/{id}
    methods: [GET,POST]
    defaults:
        _controller: enhavo_taxonomy.controller.taxonomy:updateAction
        _sylius:
            viewer:
                translationDomain: EnhavoTaxonomyBundle
                tabs:
                    main:
                        label: taxonomy.label.taxonomy
                        template: EnhavoTaxonomyBundle:Tab:taxonomy.html.twig

<?= $bundle_name; ?>_<?= $name; ?>_table:
    options:
        expose: true
    path:  /<?= $bundle_url; ?>/<?= $name_url; ?>/table
    defaults:
        _controller: enhavo_taxonomy.controller.taxonomy:tableAction
        _sylius:
            repository:
                method: findByTaxonomy
                arguments:
                    criteria:
                        taxonomy: <?= $name; ?>
            viewer:
                translationDomain: EnhavoTaxonomyBundle
                width: 12
                columns:
                    name:
                        type: property
                        width: 12
                        label: taxonomy.label.name
                        property: name

<?= $bundle_name; ?>_<?= $name; ?>_delete:
    options:
        expose: true
    path: /<?= $bundle_url; ?>/<?= $name_url; ?>/delete/{id}
    methods: [POST]
    defaults:
        _controller: enhavo_taxonomy.controller.taxonomy:deleteAction

<?= $bundle_name; ?>_<?= $name; ?>_batch:
    options:
        expose: true
    path: /<?= $bundle_url; ?>/<?= $name_url; ?>/batch
    methods: [POST]
    defaults:
        _controller: enhavo_taxonomy.controller.taxonomy:batchAction
        _sylius:
            paginate: false
            criteria:
                id: $ids
            batches:
                delete:
                    type: delete
