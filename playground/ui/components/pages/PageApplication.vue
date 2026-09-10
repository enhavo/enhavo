<template>
    <div>
        <h1>Application</h1>

        <e-app>
            <e-toolbar>
                <e-toolbar-brand :collapsed="menuCollapsed"></e-toolbar-brand>
                <e-toolbar-left>
                    <button @click="menuCollapsed = !menuCollapsed">Toogle Menu</button>
                </e-toolbar-left>
                <e-toolbar-right>
                    <e-toolbar-action :action="menuPage"></e-toolbar-action>
                    <e-toolbar-action-dropdown :action="{label: 'Lang'}"></e-toolbar-action-dropdown>
                </e-toolbar-right>
            </e-toolbar>
            <e-menu :collapsed="menuCollapsed">
                <e-menu-main>
                    <e-menu-item :item="menuPage" />
                    <e-menu-item-list :item="menuArticle" />
                </e-menu-main>
                <e-menu-bottom>
                    <e-menu-item :item="menuUser" />
                </e-menu-bottom>
            </e-menu>
            <e-frames>
                <e-frame :frame="frameOne">Das ist ein Frame</e-frame>
                <e-frame :frame="frameTwo">Das ist ein Frame</e-frame>
            </e-frames>
        </e-app>
    </div>
</template>

<script lang="ts" setup>
import {ref, reactive} from "vue";

const menuCollapsed = ref(false)

const menuPage = reactive({
    "key": "page",
    "label": "Page",
    "component": "e-menu-item",
    "open": () => {
        console.log('open page');
    },
});

const menuArticle = reactive({
    "key": "article",
    "label": "Article",
    "component": "e-menu-item",
    "expanded": false,
    "open": function() {
        this.expanded = !this.expanded;
    },
    children: [
        {
            "key": "category",
            "label": "Category",
            "component": "e-menu-item",
            "open": () => {
                console.log('open category');
            }
        }
    ]
});

const menuUser = reactive({
    "key": "user",
    "label": "User",
    "component": "e-menu-item",
    "open": () => {
        console.log('open user');
    }
});

const frameOne = reactive({
    "id": "b9f3cbd2-b7b8-4f08-afb2-3799bfa7a07f",
    "component": "e-frame-iframe",
    "url": "/admin/dashboard/dashboard",
    "parent": null,
    "label": "Dashboard",
    "wait": true,
    "keepAlive": false,
    "display": true,
    "closeable": true,
    "minimize": false,
    "position": 0,
    "width": 30,
    "loaded": true,
    "keepMinimized": false,
    "focus": false,
    "removed": false
});

const frameTwo = reactive({
    "id": "cb028e50-0555-4753-b5ca-bfba6e79fdbb",
    "component": "e-frame",
    "parent": null,
    "label": "cb028e50-0555-4753-b5ca-bfba6e79fdbb",
    "wait": true,
    "keepAlive": false,
    "display": true,
    "closeable": true,
    "minimize": false,
    "position": 1,
    "width": null,
    "loaded": true,
    "keepMinimized": false,
    "focus": true,
    "removed": false
});

</script>
