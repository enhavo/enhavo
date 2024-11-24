<?= $route_prefix ?>_index:
    path: <?= $path_prefix ?>/index
    methods: [GET]
    defaults:
        _expose: <?= $area."\n" ?>
        _vue:
            component: resource-index
            groups: <?= $area."\n" ?>
            meta:
                api: <?= $route_api_prefix ?>_index
        _endpoint:
            type: admin

<?= $route_prefix; ?>_create:
    path: <?= $path_prefix ?>/create
    methods: [GET]
    defaults:
        _expose: <?= $area."\n" ?>
        _vue:
            component: resource-input
            groups: <?= $area."\n" ?>
            meta:
                api: <?= $route_api_prefix ?>_create
        _endpoint:
            type: admin

<?= $route_prefix; ?>_update:
    path: <?= $path_prefix ?>/update/{id}
    methods: [GET]
    defaults:
        _expose: <?= $area."\n" ?>
        _vue:
            component: resource-input
            groups: <?= $area."\n" ?>
            meta:
                api: <?= $route_api_prefix ?>_update
        _endpoint:
            type: admin
