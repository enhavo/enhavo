<?php echo $route_prefix; ?>_index:
    path: <?php echo $path_prefix; ?>/index
    methods: [GET]
    defaults:
        _expose: <?php echo $area."\n"; ?>
        _vue:
            component: resource-index
            groups: <?php echo $area."\n"; ?>
            meta:
                api: <?php echo $route_api_prefix; ?>_index
        _endpoint:
            type: admin

<?php echo $route_prefix; ?>_create:
    path: <?php echo $path_prefix; ?>/create
    methods: [GET]
    defaults:
        _expose: <?php echo $area."\n"; ?>
        _vue:
            component: resource-input
            groups: <?php echo $area."\n"; ?>
            meta:
                api: <?php echo $route_api_prefix; ?>_create
        _endpoint:
            type: admin

<?php echo $route_prefix; ?>_update:
    path: <?php echo $path_prefix; ?>/update/{id}
    methods: [GET]
    defaults:
        _expose: <?php echo $area."\n"; ?>
        _vue:
            component: resource-input
            groups: <?php echo $area."\n"; ?>
            meta:
                api: <?php echo $route_api_prefix; ?>_update
        _endpoint:
            type: admin
