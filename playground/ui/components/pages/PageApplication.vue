<template>
    <div>
        <h1>Application</h1>

        <div class="e-app">

            <div class="e-toolbar">
                <div class="toolbar-brand is-collapsed"> Brand </div>
                <div class="toolbar-left">
                    <button>Toogle Menu</button>
                </div>
                <div class="toolbar-right"></div>
                <div class="e-btn"></div>
            </div>

            <div class="e-menubar">
                <div class="menubar-main">
                    <div class="menu-item-list">
                        <div class="e-menu-item">Page</div>

                        <div class="e-menu-item-list">
                            <div class="menu-item">Article</div>
                            <div class="menu-item-list-container is-expanded">
                                <div class="e-menu-item">Tag</div>
                                <div class="e-menu-item">Category</div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="menu-bottom">

                </div>
            </div>

            <div class="e-frames">
                <div class="frame">
                </div>
            </div>
        </div>
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
